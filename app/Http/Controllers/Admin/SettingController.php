<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Setting::class);

        $groupOrder = ['general', 'contact', 'social', 'seo', 'adsense', 'system'];

        $settings = Setting::all()
            ->groupBy('group')
            ->sortBy(fn ($_, string $group) => array_search($group, $groupOrder) !== false ? array_search($group, $groupOrder) : 99);

        return view('admin.settings.index', compact('settings'));
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            if ($request->user()->cannot('update', $setting)) {
                continue;
            }

            if ($setting->type === 'boolean' || $setting->type === 'bool') {
                $setting->update(['value' => $request->boolean($setting->key) ? '1' : '0']);
            } elseif ($request->has($setting->key)) {
                $setting->update(['value' => $request->input($setting->key)]);
            }
        }

        foreach ($request->except('_token', '_method') as $key => $value) {
            if ($settings->contains('key', $key) || ! in_array($key, Setting::allowedKeys(), true)) {
                continue;
            }

            if ($request->user()->isSuperAdmin()) {
                $valueString = is_array($value) ? json_encode($value) : (string) $value;

                if (mb_strlen($key) > 255 || mb_strlen($valueString) > 5000) {
                    continue;
                }

                Setting::create([
                    'key' => $key,
                    'value' => $value,
                    'group' => 'general',
                    'type' => 'text',
                ]);
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
