<?php

use App\Http\Controllers\PaymentCallbackController;
use App\Livewire\Checkout;
use App\Livewire\Home;
use App\Livewire\Products;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProductShow;

Route::get('/', Home::class )->name('home');
Route::get('products', Products::class)->name('products');
Route::get('/product/{category_slug}/{product_slug}', ProductShow::class)->name('products.show');
Route::get('checkout', Checkout::class)->name('checkout');
Route::get('/payment/callback', PaymentCallbackController::class)->name('payment.callback');

// Checkout
Route::get('/checkout', Checkout::class)->name('checkout');

Route::get('/shop', function () {
    return true;
})->name('shop');

// User Orders
Route::get('/user/orders', function () {
    return true;
})->middleware('auth')->name('user.orders');

