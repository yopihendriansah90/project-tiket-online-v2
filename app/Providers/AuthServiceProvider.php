<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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

        // Secara implisit memberikan semua akses kepada Super Admin
        // Metode ini akan berjalan sebelum semua pengecekan policy lainnya
        Gate::before(function (User $user, string $ability) {
            // Jika user memiliki role 'Super Admin', kembalikan true.
            // Spatie Shield akan menerjemahkan ini sebagai "akses penuh".
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
