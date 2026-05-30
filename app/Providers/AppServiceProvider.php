<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\CartItem;
use App\Models\Wishlist;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $hCart         = CartItem::with('product')->where('user_id', auth()->id())->take(3)->get();
                $hTotal        = $hCart->sum(fn($i) => ($i->product->price ?? 0) * $i->quantity);
                $wishlistCount = Wishlist::where('user_id', auth()->id())->count();
            } else {
                $hCart         = collect();
                $hTotal        = 0;
                $wishlistCount = 0;
            }
            $view->with(compact('hCart', 'hTotal', 'wishlistCount'));
        });
    }
}