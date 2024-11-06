<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monicahq\Cloudflare\LaravelCloudflare;
use Monicahq\Cloudflare\Facades\CloudflareProxies;

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
        LaravelCloudflare::getProxiesUsing(fn() => CloudflareProxies::load());
    }
}
