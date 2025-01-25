<?php

use App\Livewire\Auth\{Login, Logout, Password\Recovery, Password\Reset, Register};
use App\Livewire\{Empresas, Users, Welcome};
use Illuminate\Support\Facades\Route;

//region login
Route::get('/login', Login::class)->name('login');
Route::get('/password/recovery', Recovery::class)->name('password.recovery');
Route::get('/password/reset', Reset::class)->name('password.reset');
//endregion

//region Auth
Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');
    Route::get('/register', Register::class)->name('auth.register');
    Route::get('/logout', [Logout::class, 'logout'])->name('logout');
    Route::get('/user/edit/{id}', Users\Update::class)->name('user.edit');
    Route::get('/user/create', Users\Create::class)->name('user.create');

    //region Admin
    Route::middleware('role:admin|empresas')->group(function () {
        Route::get('/dashboard', fn () => 'admin dashboard')->name('admin.dashboard');
        Route::get('/users', Users\Index::class)->name('user.list');
        Route::get('/emppresas', Empresas\Index::class)->name('empresas.list');
        Route::get('/emppresa/show/{id}', Empresas\Show::class)->name('empresas.show');
    });
    //endregion

});
//endregion
