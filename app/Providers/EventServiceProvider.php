<?php

namespace App\Providers;

use App\Listeners\MigrateGuestDataListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            MigrateGuestDataListener::class . '@handleRegistration',
        ],
        Login::class => [
            MigrateGuestDataListener::class . '@handleLogin',
        ],
    ];
}