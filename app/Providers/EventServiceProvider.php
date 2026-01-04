<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\RestoreCartOnLogin;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            RestoreCartOnLogin::class,
        ],

    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}