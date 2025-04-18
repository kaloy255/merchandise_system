<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading();

        //global authenticated user
        View::composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });
        //authorization for admin
        Gate::define('admin-access', function (User $user) {
            return $user->is_admin;
        });
         //authorization for customer
        Gate::define('not-admin', function ($user) {
            return !$user->is_admin;
        });
    }
}
