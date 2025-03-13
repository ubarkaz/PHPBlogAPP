<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Blog::class => BlogPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('access-dashboard', function (User $user) {
            return $user->email === 'kasoziubar97@gmail.com'; 
        });
    }
}
