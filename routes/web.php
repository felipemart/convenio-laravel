<?php

use App\Enum\RoleEnum;
use App\Livewire\Auth\{Login, Logout, Password\Recovery, Password\Reset, Register};
use App\Livewire\Welcome;
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

    //region Admin
    Route::prefix('/admin')->middleware('role:' . RoleEnum::ADMIN->value)->group(function () {
        Route::get('/dashboard', fn () => 'admin dashboard')->name('admin.dashboard');
    });
    //endregion

});
//endregion
