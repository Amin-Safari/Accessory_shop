<?php

use App\Livewire\Home;
//use App\Livewire\User\SignUp;
use App\Livewire\Products;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProductShow;

Route::get('/', Home::class )->name('home');
Route::get('products', Products::class)->name('products');
Route::get('/product/{slug}', ProductShow::class)->name('products.show');
