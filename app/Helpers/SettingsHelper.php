<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class SettingsHelper
{
    protected static ?Setting $setting = null;

    public static function get(): ?Setting
    {
        if (static::$setting !== null) {
            return static::$setting;
        }

        try {
            if (! Schema::hasTable('settings')) {
                return null;
            }

            static::$setting = Setting::first();
        } catch (\Throwable) {
            static::$setting = null;
        }

        return static::$setting;
    }

    public static function brandName(): string
    {
        $setting = static::get();

        if ($setting?->company_name) {
            return $setting->company_name;
        }

        return config('app.system_name', config('app.name', 'Baqalah ERP'));
    }

    public static function brandSubtitle(): string
    {
        return 'ERP Software';
    }

    public static function logoUrl(): ?string
    {
        return static::get()?->logo_url;
    }

    public static function brandInitial(): string
    {
        return strtoupper(substr(static::brandName(), 0, 1));
    }
}
