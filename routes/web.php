<?php

use App\Livewire\Home;
use App\Livewire\User\SignUp;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class )->name('home');

Route::prefix('user')->group(function () {
    Route::get('sign-up', SignUp::class )->name('user.sign-up');
});
