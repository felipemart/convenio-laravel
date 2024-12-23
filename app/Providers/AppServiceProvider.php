<?php

namespace App\Providers;

use App\Enum\RoleEnum;
use Illuminate\Support\Facades\{Gate};
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->callAfterResolving('blade.compiler', fn (BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler));
    }

    public function boot(): void
    {
        foreach (RoleEnum::cases() as $role) {
            Gate::define(
                $role->value,
                fn ($user) => $user->hasRole($role)
            );
        }
    }
    public static function bladeMethodWrapper($method, $role, $guard = null): bool
    {
        return auth($guard)->check() && auth($guard)->user()->{$method}($role);
    }
    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
        // permission checks
        $bladeCompiler->if('haspermission', fn () => $this->bladeMethodWrapper('hasPermission', ...func_get_args()));
        $bladeCompiler->if('permission', fn () => $this->bladeMethodWrapper('hasPermission', ...func_get_args()));
        // role checks
        $bladeCompiler->if('role', fn () => $this->bladeMethodWrapper('hasRole', ...func_get_args()));
        $bladeCompiler->if('hasrole', fn () => $this->bladeMethodWrapper('hasRole', ...func_get_args()));
        //        $bladeCompiler->if('hasanyrole', fn () => $this->bladeMethodWrapper('hasAnyRole', ...func_get_args()));
        //        $bladeCompiler->if('hasallroles', fn () => $this->bladeMethodWrapper('hasAllRoles', ...func_get_args()));
        //        $bladeCompiler->if('hasexactroles', fn () => $this->bladeMethodWrapper('hasExactRoles', ...func_get_args()));
        $bladeCompiler->directive('endunlessrole', fn () => '<?php endif; ?>');
    }

}
