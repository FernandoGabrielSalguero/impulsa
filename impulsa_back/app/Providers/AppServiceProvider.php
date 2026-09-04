<?php

namespace App\Providers;

use App\Services\Mailbox\HostingerImapSmtpGateway;
use App\Services\Mailbox\HostingerMailGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HostingerMailGateway::class, HostingerImapSmtpGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
