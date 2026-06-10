<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    public function get(): Setting
    {
        return Setting::first() ?? new Setting();
    }

    public function save(array $data, ?UploadedFile $logo = null, bool $removeLogo = false): Setting
    {
        $setting = Setting::first() ?? new Setting();

        if ($removeLogo && $setting->logo) {
            Storage::disk('public')->delete($setting->logo);
            $data['logo'] = null;
        }

        if ($logo) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $logo->store('settings', 'public');
        }

        unset($data['remove_logo']);

        if ($setting->exists) {
            $setting->update($data);
        } else {
            $setting = Setting::create($data);
        }

        return $setting->fresh();
    }
}
