<?php

namespace App\Console\Commands;

use App\Models\Promo;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

#[Signature('promos:clear-stale-highlights')]
#[Description('Clear is_highlighted from promos that are inactive or expired')]
class ClearStalePromoHighlights extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $updated = Promo::query()
            ->where('is_highlighted', true)
            ->where(function (Builder $query) {
                $query->where('is_active', false)
                    ->orWhere(fn (Builder $q) => $q
                        ->whereNotNull('valid_until')
                        ->where('valid_until', '<', now()));
            })
            ->update(['is_highlighted' => false]);

        if ($updated > 0) {
            Cache::store('api')->flush();
        }

        $this->info("Cleared stale highlights from {$updated} promo(s).");

        return self::SUCCESS;
    }
}
