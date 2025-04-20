<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//admin
Route::middleware(['auth', 'verified', 'can:admin-access'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Products
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/admin/create', [ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('/admin/products/{id}', [ProductController::class, 'find'])->name('products.find');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Orders
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/orders/{id}', [AdminController::class, 'orderShow'])->name('admin.orders.show');
    Route::patch('/admin/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update-status');
    
    // Users
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    
    // Notifications
    Route::get('/admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications');
    Route::patch('/admin/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::patch('/admin/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.read-all');
});

//cutomer
Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
    Route::get('/products/{id}', [ProductController::class, 'find'])->name('products.find');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])
    ->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])
    ->name('cart.update');
    Route::delete('/cart/multiple', [CartController::class, 'removeMultiple'])
    ->name('cart.removeMultiple');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])
        ->name('cart.remove');

    //checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])
    ->name('checkout.process');
    Route::post('/checkout/quick', [CheckoutController::class, 'quickCheckout'])
    ->name('checkout.quick');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])
    ->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])
    ->name('orders.show');
    Route::get('/orders/{id}/confirmation', [OrderController::class, 'confirmation'])
    ->name('orders.confirmation');
});

//settings
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//sing in provider
Route::controller(SocialiteController::class)->group(function() {
    Route::get('auth/redirection/{provider}', 'redirectToProvider')->name('auth.redirection');
    Route::get('auth/{provider}/callback', 'handleProviderCallback')->name('auth.callback');
});


require __DIR__.'/auth.php';
