<?php

namespace App\Providers;

use App\Models\CartItem;
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
       

        //global authenticated user
        View::composer('*', function ($view) {
            $user = Auth::user();
        
            $cartCount = 0;
        
            if ($user) {
                $cartCount = CartItem::where('user_id', $user->id)->count();
            }
        
            $view->with('authUser', $user);
            $view->with('cartCount', $cartCount);
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
