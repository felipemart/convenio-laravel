<?php

declare(strict_types = 1);

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Password\Create;
use App\Livewire\Auth\Password\Recovery;
use App\Livewire\Auth\Password\Reset;
use App\Livewire\Auth\Register;
use App\Livewire\Empresas;
use App\Livewire\User;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

//region login
Route::get('/login', Login::class)->name('login');
Route::get('/password/recovery', Recovery::class)->name('password.recovery');
Route::get('/password/reset', Reset::class)->name('password.reset');
Route::get('/password/create', Create::class)->name('password.create');
//endregion

//region Auth
Route::middleware('auth')->group(function (): void {
    Route::get('/', Welcome::class)->name('dashboard');
    Route::get('/register', Register::class)->name('auth.register');
    Route::get('/logout', Logout::class)->name('logout');
    Route::get('/user/edit/{id}', User\Update::class)->name('user.edit');
    Route::get('/user/create', User\Create::class)->name('user.create');

    //region Admin
    Route::middleware('role:admin|empresas')->group(function (): void {
        Route::get('/dashboard', fn (): string => 'admin dashboard')->name('admin.dashboard');
        Route::get('/users', User\Index::class)->name('user.list');
        Route::get('/empresas', Empresas\Index::class)->name('empresas.list');
        Route::get('/emppresa/show/{id}', Empresas\Show::class)->name('empresas.show');
        Route::get('/emppresa/create', Empresas\Create::class)->name('empresas.create');
        Route::get('/emppresa/edit/{id}', Empresas\Update::class)->name('emppresa.edit');
    });
    //endregion
});
//endregion
