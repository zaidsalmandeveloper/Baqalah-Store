<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class SettingService
{
    protected string $logoDirectory = 'uploads/settings';

    public function get(): Setting
    {
        return Setting::first() ?? new Setting();
    }

    public function save(array $data, ?UploadedFile $logo = null, bool $removeLogo = false): Setting
    {
        $setting = Setting::first() ?? new Setting();

        unset($data['logo'], $data['remove_logo']);

        if ($removeLogo && $setting->logo) {
            $this->deleteLogoFile($setting->logo);
            $data['logo'] = null;
        }

        if ($logo) {
            if ($setting->logo) {
                $this->deleteLogoFile($setting->logo);
            }
            $data['logo'] = $this->storeLogo($logo);
        }

        if ($setting->exists) {
            $setting->update($data);
        } else {
            $setting = Setting::create($data);
        }

        return $setting->fresh();
    }

    protected function storeLogo(UploadedFile $logo): string
    {
        $directory = public_path($this->logoDirectory);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $logo->getClientOriginalName());
        $logo->move($directory, $filename);

        return $this->logoDirectory.'/'.$filename;
    }

    protected function deleteLogoFile(string $path): void
    {
        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }

        // Clean up files saved via the old storage disk approach.
        if (str_starts_with($path, 'settings/')) {
            $legacyPath = storage_path('app/public/'.$path);
            if (File::exists($legacyPath)) {
                File::delete($legacyPath);
            }
        }
    }
}
