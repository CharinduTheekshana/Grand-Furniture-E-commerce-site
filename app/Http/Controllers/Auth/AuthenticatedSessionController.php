<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Admin redirect
        if (auth()->user()->email === env('ADMIN_EMAIL', 'admin@gmail.com')) {
            return redirect('/admin');
        }

        $intendedAction  = session('intended_action');
        $intendedProduct = session('intended_product');
        $intendedQty     = session('intended_qty', 1);

        \Log::info('Intended:', [
            'action'  => $intendedAction,
            'product' => $intendedProduct,
            'qty'     => $intendedQty,
        ]);

        // Checkout — product_id not needed
        if ($intendedAction === 'checkout') {
            session()->forget(['intended_action', 'intended_product', 'intended_qty']);
            return redirect()->route('checkout.index');
        }

        // Cart / Wishlist — need product_id
        if ($intendedAction && $intendedProduct) {
            session()->forget(['intended_action', 'intended_product', 'intended_qty']);

            if ($intendedAction === 'cart') {
                $cartItem = CartItem::where('user_id', auth()->id())
                    ->where('product_id', $intendedProduct)->first();
                if ($cartItem) {
                    $cartItem->increment('quantity', $intendedQty);
                } else {
                    CartItem::create([
                        'user_id'    => auth()->id(),
                        'product_id' => $intendedProduct,
                        'quantity'   => $intendedQty,
                    ]);
                }
                return redirect()->route('cart.index')->with('success', 'Product added to cart!');
            }

            if ($intendedAction === 'wishlist') {
                $existing = Wishlist::where('user_id', auth()->id())
                    ->where('product_id', $intendedProduct)->first();
                if (!$existing) {
                    Wishlist::create(['user_id' => auth()->id(), 'product_id' => $intendedProduct]);
                }
                return redirect()->route('wishlist.index')->with('success', 'Product added to wishlist!');
            }
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}