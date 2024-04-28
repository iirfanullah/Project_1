<?php

namespace App\Providers;

use App\Services\GoogleCalendarService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\GoogleCalendarServiceInterface;

class GoogleCalendarProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(GoogleCalendarServiceInterface::class, GoogleCalendarService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
