<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 1. Super Admin Bypass
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // 2. Define Dynamic Gates
        if (!app()->runningInConsole() && \Illuminate\Support\Facades\Schema::hasTable('permissions')) {
            // Cache permissions? For now, fetch all.
            try {
                $permissions = \App\Models\Permission::all();
                foreach ($permissions as $permission) {
                    \Illuminate\Support\Facades\Gate::define($permission->slug, function ($user) use ($permission) {
                        return $user->hasPermission($permission->slug, request()->attributes->get('current_organization'));
                    });
                }
            } catch (\Exception $e) {
                // Ignore during migration handling if partial
            }
        }
    }
}
