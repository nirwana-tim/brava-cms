<?php

namespace App\Models;

use App\Traits\ClearsApiCache;
use Database\Factories\TeamMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    /** @use HasFactory<TeamMemberFactory> */
    use ClearsApiCache, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'position', 'avatar', 'email', 'phone',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected static function booted(): void
    {
        static::deleting(function (TeamMember $teamMember) {
            if ($teamMember->user) {
                $teamMember->user->delete();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
