<?php

namespace App\Providers;

use App\View\Components\MobileNavLink;
use App\View\Components\NavLink;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
    public function boot()
    {
        // Share cart count with all views
        View::composer('*', function ($view) {
            $cart = json_decode(request()->cookie('cart', '[]'), true);
            $cartCount = collect($cart)->sum('quantity');
            $view->with('cartCount', $cartCount);
        });

        // // Blade components
        // Blade::component('nav-link', NavLink::class);
        // Blade::component('mobile-nav-link', MobileNavLink::class);
    }
}
