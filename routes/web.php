<?php

use App\Http\Controllers\PaymentCallbackController;
use App\Livewire\Account;
use App\Livewire\Account\Dashboard;
use App\Livewire\Account\Favorites;
use App\Livewire\Account\Notifications;
use App\Livewire\Account\OrderDetail;
use App\Livewire\Account\Orders;
use App\Livewire\Account\Profile;
use App\Livewire\Account\Transactions;
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

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/orders', Orders::class)->name('orders');
    Route::get('/orders/{order}', OrderDetail::class)->name('orders.show');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/transactions', Transactions::class)->name('transactions');
    Route::get('/favorites', Favorites::class)->name('favorites');
    Route::get('/notifications', Notifications::class)->name('notifications');
});
