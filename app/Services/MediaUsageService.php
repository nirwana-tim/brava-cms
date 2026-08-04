<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Media;
use App\Models\PortfolioItem;
use App\Models\Promo;
use App\Models\Service;
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

    /**
     * Media linked via the polymorphic `mediable` relation (e.g. portfolio gallery).
     *
     * @var array<class-string, array{table: string, label: string, title: string}>
     */
    private const MEDIABLE_MAP = [
        PortfolioItem::class => ['table' => 'portfolio_items', 'label' => 'Portfolio', 'title' => 'title'],
        Blog::class => ['table' => 'blogs', 'label' => 'Blog', 'title' => 'title'],
        Service::class => ['table' => 'services', 'label' => 'Service', 'title' => 'title'],
        Promo::class => ['table' => 'promos', 'label' => 'Promo', 'title' => 'title'],
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

        return array_values(array_unique([...$summary, ...$this->morphUsage($media)]));
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
                        if (str_contains($value, '/'.basename($item->path))) {
                            $usage[$item->id][] = self::MODULE_LABELS[$table].' "'.$row->{self::TITLE_COLUMNS[$table]}.'"';
                        }
                    }
                }
            }
        }

        foreach ($this->morphUsageBatch($media) as $id => $labels) {
            $usage[$id] = array_merge($usage[$id] ?? [], $labels);
        }

        foreach ($media as $item) {
            $item->in_use = isset($usage[$item->id]);
            $item->usage = array_values(array_unique($usage[$item->id] ?? []));
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
                        $query->orWhere($column, 'like', '%/'.$basename.'%');
                    }
                }
            })
            ->get();
    }

    /**
     * @return list<string>
     */
    private function morphUsage(Media $media): array
    {
        if ($media->mediable_type === null || $media->mediable_id === null || ! isset(self::MEDIABLE_MAP[$media->mediable_type])) {
            return [];
        }

        $config = self::MEDIABLE_MAP[$media->mediable_type];
        $row = DB::table($config['table'])->where('id', $media->mediable_id)->first();

        if (! $row) {
            return [];
        }

        return [$config['label'].' "'.$row->{$config['title']}.'"'];
    }

    /**
     * Resolve morph-linked usage labels for a batch of media.
     *
     * @return array<int, list<string>>
     */
    private function morphUsageBatch(EloquentCollection $media): array
    {
        $linked = DB::table('media')
            ->whereIn('id', $media->pluck('id'))
            ->whereNotNull('mediable_id')
            ->whereNotNull('mediable_type')
            ->select(['id', 'mediable_type', 'mediable_id'])
            ->get();

        $usage = [];

        foreach ($linked->groupBy('mediable_type') as $type => $rows) {
            if (! isset(self::MEDIABLE_MAP[$type])) {
                continue;
            }

            $config = self::MEDIABLE_MAP[$type];
            $titles = DB::table($config['table'])
                ->whereIn('id', $rows->pluck('mediable_id'))
                ->pluck($config['title'], 'id');

            foreach ($rows as $row) {
                if ($titles->has($row->mediable_id)) {
                    $usage[$row->id][] = $config['label'].' "'.$titles[$row->mediable_id].'"';
                }
            }
        }

        return $usage;
    }
}
