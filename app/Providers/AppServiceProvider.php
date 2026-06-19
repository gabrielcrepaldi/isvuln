<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Event;
use App\Models\AuditLog;

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
        Event::listen(Login::class, function (Login $event) {
        AuditLog::record('login', $event->user);
    });

    Event::listen(Logout::class, function (Logout $event) {
        if ($event->user) {
            AuditLog::record('logout', $event->user);
        }
    });

    Event::listen(Failed::class, function (Failed $event) {
        // No authenticated user — record the attempted email in new_values
        AuditLog::record(
            'login_failed',
            null,
            null,
            ['email' => $event->credentials['email'] ?? 'unknown']
        );
    });
  }
}
