<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Services\Api\GuestDataMigrationService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class MigrateGuestDataListener
{
    public function __construct(
        private GuestDataMigrationService $migrationService,
        private Request $request
    ) {}

    /**
     * Handle user login events.
     * Runs in the same request where cookies are present—so decryption middleware must be active.
     */
    public function handleLogin(Login $event): void
    {
        // Fire-and-forget; you can log/monitor the returned array if desired.
        $this->migrationService->migrateGuestDataToUser($this->request, $event->user);
    }

    /**
     * Handle user registration events.
     */
    public function handleRegistration(Registered $event): void
    {
        $this->migrationService->migrateGuestDataToUser($this->request, $event->user);
    }
}
