<?php

declare(strict_types = 1);

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Password\Create;
use App\Livewire\Auth\Password\Recovery;
use App\Livewire\Auth\Password\Reset;
use App\Livewire\Auth\Register;
use App\Livewire\Conveniada;
use App\Livewire\Convenio;
use App\Livewire\Operadora;
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

    Route::middleware('role:admin|operadora|convenio')->group(function (): void {
        Route::get('/user/edit/{id}', User\Update::class)->name('user.edit');
        Route::get('/user/create', User\Create::class)->name('user.create');
        Route::get('/users', User\Index::class)->name('user.list');
    });

    //region Admin

    Route::get('/dashboard', fn (): string => 'admin dashboard')->name('admin.dashboard');

    Route::middleware('role:admin|operadora')->group(function (): void {
        Route::get('/operadora', Operadora\Index::class)->name('operadora.list');
        Route::get('/operadora/show/{id}', Operadora\Show::class)->name('operadora.show');
        Route::get('/operadora/create', Operadora\Create::class)->name('operadora.create');
        Route::get('/operadora/edit/{id}', Operadora\Update::class)->name('operadora.edit');
    });
    Route::middleware('role:admin|operadora|convenio')->group(function (): void {
        Route::get('/convenio/create', Convenio\Create::class)->name('convenio.create');
        Route::get('/convenio/{id?}', Convenio\Index::class)->name('convenio.list');
        Route::get('/convenio/show/{id}', Convenio\Show::class)->name('convenio.show');
        Route::get('/convenio/edit/{id}', Convenio\Update::class)->name('convenio.edit');
    });

    Route::middleware('role:admin|operadora|convenio|conveniada')->group(function (): void {
        Route::get('/conveniada/create', Conveniada\Create::class)->name('conveniada.create');
        Route::get('/conveniada/{id?}', Conveniada\Index::class)->name('conveniada.list');
        Route::get('/conveniada/show/{id}', Conveniada\Show::class)->name('conveniada.show');
        Route::get('/conveniada/edit/{id}', Conveniada\Update::class)->name('conveniada.edit');
    });
    //endregion
});
//endregion
