<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;

Route::post('/admin/logout', function() {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('filament.admin.auth.logout');

// Language switcher route
Route::get('/locale/{locale}', [App\Http\Controllers\LocaleController::class, 'switch'])
    ->name('locale.switch');

// Save intended action for guest users
Route::post('/save-intended', function(\Illuminate\Http\Request $request) {
    session([
        'intended_action'  => $request->action,
        'intended_product' => $request->product_id,
        'intended_qty'     => $request->qty ?? 1,
    ]);
    return response()->json(['ok' => true]);
})->name('save.intended');

// ─── PUBLIC ROUTES ─────────────────────────────────────────────
Route::get('/',               [ShopController::class, 'index'])->name('home');
Route::get('/shop',           [ShopController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/blog',           [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}',    [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact',        [ContactController::class, 'index'])->name('contact');
Route::post('/contact',       [ContactController::class, 'send'])->name('contact.send');

// ─── CART + WISHLIST (guests → login redirect) ─────────────────
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// ─── AUTH REQUIRED ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/cart',           [CartController::class, 'index'])->name('cart.index');
    Route::patch('/cart/{item}',  [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove')->whereNumber('item');

    Route::get('/wishlist',  [WishlistController::class, 'index'])->name('wishlist.index');

    Route::get('/checkout',  [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders',          [CheckoutController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}',  [CheckoutController::class, 'show'])->name('orders.show');

    Route::get('/reviews', [ProductController::class, 'myReviews'])->name('reviews.index');
});

// ─── CUSTOM ADMIN PANEL ────────────────────────────────────────
// Note: Uses auth middleware only — restrict to admin users via is_admin column or Filament auth
Route::middleware(['auth'])
    ->prefix('admin-panel')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::get('/products',                   [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create',            [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products',                  [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit',    [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}',         [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}',      [AdminProductController::class, 'destroy'])->name('products.destroy');

        // Blog
        Route::get('/blog',                       [AdminBlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/create',                [AdminBlogController::class, 'create'])->name('blog.create');
        Route::post('/blog',                      [AdminBlogController::class, 'store'])->name('blog.store');
        Route::get('/blog/{blog}/edit',           [AdminBlogController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{blog}',                [AdminBlogController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{blog}',             [AdminBlogController::class, 'destroy'])->name('blog.destroy');

        // Contacts
        Route::get('/contacts',                   [AdminContactController::class, 'index'])->name('contacts.index');
    });

    // Customer Review
Route::post('/product/{slug}/review', [ProductController::class, 'review'])
    ->name('product.review')
    ->middleware('auth');

    // Payment routes
Route::get('/payment/{order}',  [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{order}', [PaymentController::class, 'process'])->name('payment.process');

    // Order confirmation route (after successful payment)
Route::post('/orders/{order}/confirm', [CheckoutController::class, 'confirm'])->name('orders.confirm');

    
require __DIR__.'/auth.php';
