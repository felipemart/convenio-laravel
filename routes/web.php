<?php

declare(strict_types = 1);

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Password\Recovery;
use App\Livewire\Auth\Password\Reset;
use App\Livewire\Auth\Register;
use App\Livewire\Empresas;
use App\Livewire\Users;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

//region login
Route::get('/login', Login::class)->name('login');
Route::get('/password/recovery', Recovery::class)->name('password.recovery');
Route::get('/password/reset', Reset::class)->name('password.reset');
//endregion

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

//region Auth
Route::middleware('auth')->group(function (): void {
    Route::get('/', Welcome::class)->name('dashboard');
    Route::get('/register', Register::class)->name('auth.register');
    Route::get('/logout', [Logout::class, 'logout'])->name('logout');
    Route::get('/user/edit/{id}', Users\Update::class)->name('user.edit');
    Route::get('/user/create', Users\Create::class)->name('user.create');

    //region Admin
    Route::middleware('role:admin|empresas')->group(function (): void {
        Route::get('/dashboard', fn (): string => 'admin dashboard')->name('admin.dashboard');
        Route::get('/users', Users\Index::class)->name('user.list');
        Route::get('/empresas', Empresas\Index::class)->name('empresas.list');
        Route::get('/emppresa/show/{id}', Empresas\Show::class)->name('empresas.show');
        Route::get('/emppresa/create', Empresas\Create::class)->name('empresas.create');
    });
    //endregion
});
//endregion
