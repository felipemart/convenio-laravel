<?php

namespace App\Providers;

use App\Enum\RoleEnum;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        foreach (RoleEnum::cases() as $role) {

            Gate::define(
                str($role->value)->snake('-')->toString(),
                fn ($user) => $user->hasRole($role)
            );
        }
    }
}
