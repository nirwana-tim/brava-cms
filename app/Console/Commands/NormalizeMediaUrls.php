<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('media:normalize-urls {--dry-run : Preview changes without applying them}')]
#[Description('Rewrite absolute http(s) media URLs stored in content to host-independent /storage/ paths.')]
class NormalizeMediaUrls extends Command
{
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->line('<comment>DRY RUN mode — tidak ada perubahan yang diterapkan.</comment>');
        }

        $changed = 0;

        foreach (config('media.referencing') as $table => $columns) {
            foreach ($columns as $column) {
                DB::table($table)
                    ->where($column, 'like', 'http%://%')
                    ->orderBy('id')
                    ->chunkById(200, function ($rows) use ($table, $column, $dryRun, &$changed) {
                        foreach ($rows as $row) {
                            $normalized = preg_replace('#https?://[^/]+(?=/storage/)#', '', (string) $row->{$column});

                            if ($normalized === $row->{$column}) {
                                continue;
                            }

                            $changed++;
                            $this->line("  {$table}.{$column} #{$row->id}: {$row->{$column}}");
                            $this->line("    → {$normalized}");

                            if (! $dryRun) {
                                DB::table($table)->where('id', $row->id)->update([$column => $normalized]);
                            }
                        }
                    });
            }
        }

        if ($dryRun) {
            $this->line("<info>{$changed} nilai siap dinormalisasi.</info>");
        } else {
            $this->info("Selesai: {$changed} nilai URL dinormalisasi menjadi relatif.");
        }

        return self::SUCCESS;
    }
}
