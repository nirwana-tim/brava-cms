<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            if ($setting->type === 'boolean' || $setting->type === 'bool') {
                $setting->update(['value' => $request->has($setting->key) ? '1' : '0']);
            } elseif ($request->has($setting->key)) {
                $setting->update(['value' => $request->input($setting->key)]);
            }
        }

        foreach ($request->except('_token', '_method') as $key => $value) {
            if (! $settings->contains('key', $key)) {
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
