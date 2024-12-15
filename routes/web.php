<?php

use App\Livewire\Auth\{Login, Logout, Password\RecuperacaoSenha, Password\ResetSenha, Registro};
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login');
Route::get('/password/recuperar', RecuperacaoSenha::class)->name('password.recuperar');
Route::get('/password/reset', ResetSenha::class)->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');
    Route::get('/registro', Registro::class)->name('auth.registro');
    Route::get('/logout', [Logout::class, 'logout'])->name('logout');

});
