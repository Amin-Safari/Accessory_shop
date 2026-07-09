<?php

use App\Livewire\Home;
//use App\Livewire\User\SignUp;
use App\Livewire\Products;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class )->name('home');
Route::get('products', Products::class)->name('products');

//Route::prefix('user')->group(function () {
//    Route::get('sign-up', SignUp::class )->name('user.sign-up');
//});
