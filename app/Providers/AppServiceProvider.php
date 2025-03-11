<?php

declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Override;

class AppServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->callAfterResolving('blade.compiler', fn (BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler));
    }

    public function boot(): void
    {
        // comment explaining why the method is empty
    }

    public static function bladeMethodWrapper($method, $role, $guard = null): bool
    {
        return auth($guard)->check() && auth($guard)->user()->{$method}($role);
    }

    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
        // permission checks
        $bladeCompiler->if('haspermission', fn (): bool => static::bladeMethodWrapper('hasPermission', ...func_get_args()));
        $bladeCompiler->if('permission', fn (): bool => static::bladeMethodWrapper('hasPermission', ...func_get_args()));
        // role checks
        $bladeCompiler->if('role', fn (): bool => static::bladeMethodWrapper('hasRole', ...func_get_args()));
        $bladeCompiler->if('hasrole', fn (): bool => static::bladeMethodWrapper('hasRole', ...func_get_args()));
        $bladeCompiler->directive('endunlessrole', fn (): string => '<?php endif; ?>');
    }
}
