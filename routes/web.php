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
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ColorController as AdminColorController;

Route::post('/admin/logout', function() {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('admin.logout');

// Save intended action for guest users
Route::post('/save-intended', function(\Illuminate\Http\Request $request) {
    session([
        'intended_action'  => $request->action,
        'intended_product' => $request->product_id,
        'intended_qty'     => $request->qty ?? 1,
        'intended_color'   => $request->color_id ?: null,
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
    Route::patch('/orders/{order}/received', [OrderController::class, 'markReceived'])->name('orders.received');

    Route::get('/reviews', [ProductController::class, 'myReviews'])->name('reviews.index');

    Route::post('/coupon/apply',  [CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');
});

// ─── CUSTOM ADMIN PANEL ────────────────────────────────────────
// Note: Uses auth middleware only — restrict to admin users via is_admin column or Filament auth
Route::middleware(['auth'])
    ->prefix('admin-panel')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Categories
        Route::get('/categories',              [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create',       [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories',             [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}',   [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}',[AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        // Products
        Route::get('/products',                   [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create',            [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products',                  [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit',    [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}',         [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}',      [AdminProductController::class, 'destroy'])->name('products.destroy');
        Route::delete('/products/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::patch('/products/images/{image}/color', [AdminProductController::class, 'assignImageColor'])->name('products.images.color');

        // Blog
        Route::get('/blog',                       [AdminBlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/create',                [AdminBlogController::class, 'create'])->name('blog.create');
        Route::post('/blog',                      [AdminBlogController::class, 'store'])->name('blog.store');
        Route::get('/blog/{blog}/edit',           [AdminBlogController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{blog}',                [AdminBlogController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{blog}',             [AdminBlogController::class, 'destroy'])->name('blog.destroy');

        // Contacts
        Route::get('/contacts',                   [AdminContactController::class, 'index'])->name('contacts.index');
        Route::delete('/contacts/{contact}',       [AdminContactController::class, 'destroy'])->name('contacts.destroy');
    
        // Customers
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');

        // Orders
        Route::get('/orders',                  [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',          [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Reviews
        Route::get('/reviews',             [AdminReviewController::class, 'index'])->name('admin-reviews.index');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('admin-reviews.destroy');

        // Coupons
        Route::get('/coupons',               [AdminCouponController::class, 'index'])->name('coupons.index');
        Route::post('/coupons',              [AdminCouponController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}',      [AdminCouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}',   [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

        // Reports
        Route::get('/reports',        [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');

        // Colors
        Route::get('/colors',              [AdminColorController::class, 'index'])->name('colors.index');
        Route::post('/colors',             [AdminColorController::class, 'store'])->name('colors.store');
        Route::post('/colors/bulk',       [AdminColorController::class, 'storeBulk'])->name('colors.bulk');
        Route::get('/colors/{color}/edit', [AdminColorController::class, 'edit'])->name('colors.edit');
        Route::put('/colors/{color}',      [AdminColorController::class, 'update'])->name('colors.update');
        Route::delete('/colors/{color}',   [AdminColorController::class, 'destroy'])->name('colors.destroy');

        // Admin global search
        Route::get('/search', [App\Http\Controllers\Admin\DashboardController::class, 'search'])->name('search');

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
Route::get('/orders/{order}/invoice', [App\Http\Controllers\InvoiceController::class, 'download'])->name('orders.invoice');

    
require __DIR__.'/auth.php';