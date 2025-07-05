<?php


namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use URL;

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
        $this->registerPolicies();

        Gate::define('isAdmin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('isUser', function (User $user) {
            return $user->isUser();
        });

        Gate::define('isProfi', function (User $user) {
            return $user->isProfi();
        });

         if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }

    }
}
