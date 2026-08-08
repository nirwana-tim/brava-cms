<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn (Model $model) => $model->recordActivity('created'));
        static::updated(fn (Model $model) => $model->recordActivity('updated'));
        static::deleted(function (Model $model) {
            $event = method_exists($model, 'isForceDeleting') && $model->isForceDeleting()
                ? 'force_deleted'
                : 'deleted';

            $model->recordActivity($event);
        });

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            static::restored(fn (Model $model) => $model->recordActivity('restored'));
        }
    }

    public function recordActivity(string $event): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'loggable_type' => $this->getMorphClass(),
            'loggable_id' => (int) $this->getKey(),
            'event' => $event,
            'description' => $this->activityDescription($event),
            'properties' => $this->activityProperties($event),
        ]);
    }

    public function activityDescription(string $event): string
    {
        $label = $this->activityLabel();

        return match ($event) {
            'created' => "\"{$label}\" created",
            'updated' => "\"{$label}\" updated",
            'deleted' => "\"{$label}\" deleted (moved to trash)",
            'restored' => "\"{$label}\" restored from trash",
            'force_deleted' => "\"{$label}\" permanently deleted",
            default => "\"{$label}\" {$event}",
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    public function activityProperties(string $event): ?array
    {
        return match ($event) {
            'updated' => [
                'old' => $this->trimActivityValues($this->getChangesFromOriginal()),
                'new' => $this->trimActivityValues($this->getChanges()),
            ],
            'deleted', 'force_deleted' => $this->trimActivityValues($this->getOriginal()) ?: null,
            default => null,
        };
    }

    public function activityLabel(): string
    {
        $prefix = class_basename($this);
        $label = $this->getAttribute('title') ?? $this->getAttribute('name');

        if (is_string($label) && $label !== '') {
            return str($label)->limit(60)->toString();
        }

        return "{$prefix} #{$this->getKey()}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function getChangesFromOriginal(): array
    {
        $changes = $this->getChanges();
        $original = [];

        foreach ($changes as $key => $value) {
            $original[$key] = $this->getOriginal($key);
        }

        return $original;
    }

    /**
     * Cap oversized values so large text columns don't bloat the audit trail.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected function trimActivityValues(array $attributes): array
    {
        return array_map(function ($value) {
            if (is_string($value) && strlen($value) > 200) {
                return substr($value, 0, 200).'...';
            }

            return $value;
        }, $attributes);
    }
}
