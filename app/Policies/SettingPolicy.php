<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Setting $setting): bool
    {
        if (in_array($setting->group, ['general', 'seo', 'system']) || in_array($setting->key, ['site_name', 'site_description', 'google_analytics_id', 'default_meta_title', 'default_meta_description'])) {
            return $user->isSuperAdmin();
        }

        return true;
    }

    public function update(User $user, Setting $setting): bool
    {
        if (in_array($setting->group, ['general', 'seo', 'system']) || in_array($setting->key, ['site_name', 'site_description', 'google_analytics_id', 'default_meta_title', 'default_meta_description'])) {
            return $user->isSuperAdmin();
        }

        return true;
    }
}
