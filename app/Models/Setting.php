<?php

namespace App\Models;

use App\Traits\ClearsApiCache;
use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use ClearsApiCache, HasFactory;

    protected $fillable = ['key', 'value', 'group', 'type'];

    public function scopeInGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
