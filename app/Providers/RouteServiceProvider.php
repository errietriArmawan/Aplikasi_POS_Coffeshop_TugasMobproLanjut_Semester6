<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register any application services if needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the API routes and web routes
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Alias middleware 'role' to RoleMiddleware
        Route::aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
    }
}
