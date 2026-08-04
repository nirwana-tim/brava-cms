<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MediaUsageService
{
    private static array $altCache = [];

    private const MODULE_LABELS = [
        'portfolio_items' => 'Portfolio',
        'blogs' => 'Blog',
        'promos' => 'Promo',
        'services' => 'Service',
        'team_members' => 'Team Member',
        'testimonials' => 'Testimonial',
        'users' => 'User',
    ];

    private const TITLE_COLUMNS = [
        'portfolio_items' => 'title',
        'blogs' => 'title',
        'promos' => 'title',
        'services' => 'title',
        'team_members' => 'name',
        'testimonials' => 'client_name',
        'users' => 'name',
    ];

    public function isInUse(Media $media): bool
    {
        return $this->usageSummary($media) !== [];
    }

    /**
     * Human readable list of content referencing the media.
     *
     * @return list<string> e.g. ['Blog "Hello World"', 'Promo "Diskon 50%"']
     */
    public function usageSummary(Media $media): array
    {
        $summary = [];

        foreach (config('media.referencing') as $table => $columns) {
            $rows = $this->rowsMatching($table, $columns, [basename($media->path)]);

            foreach ($rows as $row) {
                $summary[] = self::MODULE_LABELS[$table].' "'.$row->{self::TITLE_COLUMNS[$table]}.'"';
            }
        }

        return $summary;
    }

    /**
     * Attach in_use (bool) and usage (list<string>) to each media model.
     */
    public function markInUseBatch(EloquentCollection $media): EloquentCollection
    {
        if ($media->isEmpty()) {
            return $media;
        }

        $basenames = $media->map(fn (Media $item) => basename($item->path))->all();
        $usage = [];

        foreach (config('media.referencing') as $table => $columns) {
            $rows = $this->rowsMatching($table, $columns, $basenames);

            foreach ($rows as $row) {
                foreach ($columns as $column) {
                    $value = $row->{$column} ?? '';

                    if ($value === '') {
                        continue;
                    }

                    foreach ($media as $item) {
                        if (str_contains($value, basename($item->path))) {
                            $usage[$item->id][] = self::MODULE_LABELS[$table].' "'.$row->{self::TITLE_COLUMNS[$table]}.'"';
                        }
                    }
                }
            }
        }

        foreach ($media as $item) {
            $item->in_use = isset($usage[$item->id]);
            $item->usage = $usage[$item->id] ?? [];
        }

        return $media;
    }

    /**
     * Resolve the current alt text of the media referenced by the given URL.
     * Falls back to null when no matching media exists. Results are cached
     * per basename for the current request to avoid N+1 queries.
     */
    public function resolveAlt(?string $url): ?string
    {
        if ($url === null || $url === '') {
            return null;
        }

        $basename = basename(parse_url($url, PHP_URL_PATH) ?: $url);

        if ($basename === '' || $basename === '.') {
            return null;
        }

        if (array_key_exists($basename, self::$altCache)) {
            return self::$altCache[$basename];
        }

        return self::$altCache[$basename] = Media::query()
            ->where('path', 'like', '%'.$basename)
            ->value('alt_text');
    }

    public static function flushAltCache(): void
    {
        self::$altCache = [];
    }

    /**
     * @param  list<string>  $columns
     * @param  list<string>  $basenames
     */
    private function rowsMatching(string $table, array $columns, array $basenames): Collection
    {
        if ($basenames === []) {
            return collect();
        }

        $titleColumn = self::TITLE_COLUMNS[$table];

        return DB::table($table)
            ->select([$titleColumn, ...$columns])
            ->where(function ($query) use ($columns, $basenames) {
                foreach ($columns as $column) {
                    foreach ($basenames as $basename) {
                        $query->orWhere($column, 'like', '%'.$basename.'%');
                    }
                }
            })
            ->get();
    }
}
