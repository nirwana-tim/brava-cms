<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Signature('media:cleanup-filenames {--dry-run : Preview changes without applying them} {--remove-orphans : Delete files not referenced by content and not registered in the media library}')]
#[Description('Rename legacy media files with spaces or special characters and update every reference across content.')]
class CleanupMediaFilenames extends Command
{
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $removeOrphans = (bool) $this->option('remove-orphans');

        if ($dryRun) {
            $this->line('<comment>DRY RUN mode — tidak ada perubahan yang diterapkan.</comment>');
        } else {
            $this->warn('Pastikan database & storage sudah di-backup sebelum menjalankan ini.');
        }

        $renamed = $this->renameMediaRecords($dryRun);
        $skipped = 0;
        $renamed += $this->renameUntrackedReferenced($dryRun, $skipped);

        $this->info("Rename selesai: {$renamed} file diganti nama, {$skipped} dilewati.");

        if ($removeOrphans) {
            $this->line('');
            $this->removeOrphans($dryRun);
        }

        if (! $dryRun) {
            Cache::store('api')->flush();
        }

        return self::SUCCESS;
    }

    private function renameMediaRecords(bool $dryRun): int
    {
        $renamed = 0;

        $affected = Media::all()
            ->filter(fn (Media $media) => $this->hasUnsafeName($media->path))
            ->values();

        foreach ($affected as $media) {
            $oldPath = $media->path;
            $newPath = $this->sluggedPath($oldPath);

            if ($newPath === $oldPath) {
                continue;
            }

            $this->line("  [media] {$oldPath}");
            $this->line("    → {$newPath}");

            if ($dryRun) {
                continue;
            }

            if (! Storage::disk('public')->exists($oldPath)) {
                $this->warn('    File tidak ditemukan di disk — dilewati.');

                continue;
            }

            if (Storage::disk('public')->exists($newPath)) {
                $this->warn("    Target sudah ada: {$newPath} — dilewati.");

                continue;
            }

            Storage::disk('public')->move($oldPath, $newPath);
            $media->update(['path' => $newPath]);

            $this->replaceReferences(basename($oldPath), basename($newPath));

            $renamed++;
        }

        return $renamed;
    }

    /**
     * Rename files that are referenced by content but not registered in the
     * media library (e.g. avatars uploaded through the quick-upload flow).
     */
    private function renameUntrackedReferenced(bool $dryRun, int &$skipped): int
    {
        $registered = $this->registeredMediaPaths();
        $renamed = 0;

        foreach ($this->referencedPaths() as $path) {
            if (! Storage::disk('public')->exists($path) || isset($registered[$path]) || ! $this->hasUnsafeName($path)) {
                continue;
            }

            $newPath = $this->sluggedPath($path);

            if ($newPath === $path) {
                $skipped++;

                continue;
            }

            $this->line("  [untracked] {$path}");
            $this->line("    → {$newPath}");

            if ($dryRun) {
                continue;
            }

            if (Storage::disk('public')->exists($newPath)) {
                $this->warn("    Target sudah ada: {$newPath} — dilewati.");

                $skipped++;

                continue;
            }

            Storage::disk('public')->move($path, $newPath);
            $this->replaceReferences(basename($path), basename($newPath));

            $renamed++;
        }

        return $renamed;
    }

    private function removeOrphans(bool $dryRun): void
    {
        $registered = $this->registeredMediaPaths();
        $referenced = array_fill_keys($this->referencedPaths(), true);
        $deleted = 0;

        foreach (['uploads', 'media'] as $directory) {
            foreach (Storage::disk('public')->allFiles($directory) as $path) {
                if (isset($registered[$path]) || isset($referenced[$path])) {
                    continue;
                }

                $this->line("  [orphan] {$path}");

                if (! $dryRun) {
                    Storage::disk('public')->delete($path);
                    $deleted++;
                }
            }
        }

        if ($dryRun) {
            $this->line('<comment>DRY RUN — file yatim tidak benar-benar dihapus.</comment>');
        } else {
            $this->info("Selesai: {$deleted} file yatim dihapus.");
        }
    }

    /**
     * @return array<string, true>
     */
    private function registeredMediaPaths(): array
    {
        return Media::pluck('path')->flip()->all();
    }

    /**
     * @return list<string>
     */
    private function referencedPaths(): array
    {
        $paths = [];

        foreach (config('media.referencing') as $table => $columns) {
            foreach ($columns as $column) {
                foreach (DB::table($table)->whereNotNull($column)->where($column, '<>', '')->pluck($column) as $value) {
                    foreach ($this->extractStoragePaths((string) $value) as $path) {
                        $paths[$path] = true;
                    }
                }
            }
        }

        return array_keys($paths);
    }

    /**
     * @return list<string>
     */
    private function extractStoragePaths(string $value): array
    {
        $paths = [];

        if (preg_match('#^https?://[^/]+/storage/(.+)$#', $value, $m) || preg_match('#^/storage/(.+)$#', $value, $m)) {
            $paths[] = $this->cleanStoragePath($m[1]);
        } elseif (preg_match('#^(uploads|media)/.+$#', $value)) {
            $paths[] = $this->cleanStoragePath($value);
        }

        preg_match_all('#/storage/([^\s"\'<>]+)#', $value, $m);

        foreach ($m[1] ?? [] as $path) {
            $paths[] = $this->cleanStoragePath($path);
        }

        return array_values(array_unique($paths));
    }

    private function cleanStoragePath(string $path): string
    {
        return preg_replace('/[?#].*$/', '', $path) ?? $path;
    }

    private function sluggedPath(string $path): string
    {
        $directory = dirname($path);
        $oldName = basename($path);
        $extension = pathinfo($oldName, PATHINFO_EXTENSION);

        return $directory.'/'.Str::slug(pathinfo($oldName, PATHINFO_FILENAME)).'.'.$extension;
    }

    private function hasUnsafeName(string $path): bool
    {
        return preg_match('/[^A-Za-z0-9._\/-]/', basename($path)) === 1;
    }

    private function replaceReferences(string $oldName, string $newName): void
    {
        $variants = [
            $oldName,
            rawurlencode($oldName),
        ];

        foreach (config('media.referencing') as $table => $columns) {
            foreach ($columns as $column) {
                foreach ($variants as $variant) {
                    if ($variant === $newName) {
                        continue;
                    }

                    $updated = DB::table($table)
                        ->where($column, 'like', '%'.$variant.'%')
                        ->update([
                            $column => DB::raw('REPLACE(`'.$column.'`, '.DB::connection()->getPdo()->quote($variant).', '.DB::connection()->getPdo()->quote($newName).')'),
                        ]);

                    if ($updated > 0) {
                        $this->line("    Referensi diperbarui di {$table}.{$column} ({$updated} baris).");
                    }
                }
            }
        }
    }
}
