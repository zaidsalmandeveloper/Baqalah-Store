<?php

namespace App\Providers;

use App\Helpers\SettingsHelper;
use App\View\Components\header\NotificationDropdown;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('header.notification-dropdown', NotificationDropdown::class);

        View::share('appBrandName', SettingsHelper::brandName());
        View::share('appLogoUrl', SettingsHelper::logoUrl());
    }
}
