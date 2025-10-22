<?php

namespace App\Providers;

use App\Models\Cart;
use App\Interfaces\PaymentGatewayInterface;
use App\Services\PaymentGateway\PaystackPaymentService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Paystack Payment Service
        $this->app->singleton(PaymentGatewayInterface::class, PaystackPaymentService::class);
        $this->app->singleton(PaystackPaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())->count();
            } else {
                $cartCount = 0;
            }
            $view->with('cartCount', $cartCount);
        });

        Paginator::useBootstrap();
    }
}
