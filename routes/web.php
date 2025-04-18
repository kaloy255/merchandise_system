<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//admin
Route::middleware(['auth', 'verified', 'can:admin-access'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/admin/create', [ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('/admin/products/{id}', [ProductController::class, 'find'])->name('products.find');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
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
    Route::delete('/cart/multiple', [App\Http\Controllers\CartController::class, 'removeMultiple'])
    ->name('cart.removeMultiple');
    Route::delete('/cart/{id}', [App\Http\Controllers\CartController::class, 'remove'])
        ->name('cart.remove');
    
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
