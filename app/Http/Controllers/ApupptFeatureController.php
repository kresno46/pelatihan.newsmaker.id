<?php

namespace App\Http\Controllers;

use App\Models\ApupptFeatureSetting;

class ApupptFeatureController extends Controller
{
    public function toggle()
    {
        $setting = ApupptFeatureSetting::query()->first();

        if (! $setting) {
            $setting = ApupptFeatureSetting::create(['is_enabled' => true]);
        }

        $setting->update([
            'is_enabled' => ! $setting->is_enabled,
        ]);

        $statusText = $setting->is_enabled ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Fitur APUPPT berhasil {$statusText}.");
    }
}
