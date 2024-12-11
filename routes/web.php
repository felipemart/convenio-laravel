<?php

use App\Livewire\Auth\{Login, Logout, Register};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');
    Route::get('/register', Register::class)->name('auth.register');
    Route::get('/logout', [Logout::class, 'logout'])->name('logout');

});
