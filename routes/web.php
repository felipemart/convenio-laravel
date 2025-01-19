<?php

use App\Livewire\Auth\{Login, Logout, Password\Recovery, Password\Reset, Register};
use App\Livewire\{Operadora, Users, Welcome};
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
    Route::middleware('role:admin|operadora')->group(function () {
        Route::get('/dashboard', fn () => 'admin dashboard')->name('admin.dashboard');
        Route::get('/users', Users\Index::class)->name('user.list');
        Route::get('/operadoras', Operadora\Index::class)->name('operadora.list');
        Route::get('/operadoras/show/{id}', Operadora\Show::class)->name('operadora.show');
    });
    //endregion

});
//endregion
