<?php

namespace App\Providers;

use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Gate;
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
        Gate::before(fn (User $user, string $ability) => $user->hasPermission($ability) ?: null);

        foreach (AuditLogService::auditedModels() as $model) {
            $model::created(fn ($record) => app(AuditLogService::class)->created($record, auth()->user()));
            $model::updated(fn ($record) => app(AuditLogService::class)->updated($record, auth()->user()));
            $model::deleting(fn ($record) => app(AuditLogService::class)->deleting($record, auth()->user()));
        }
    }
}
