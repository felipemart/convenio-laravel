<?php

declare(strict_types = 1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
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
        $this->configModel();
        $this->configCommands();
        $this->configUrls();
        $this->configDates();
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

    private function configModel(): void
    {
        Model::unguard();
        Model::shouldBeStrict();
    }

    private function configCommands(): void
    {
        DB::prohibitDestructiveCommands(
            app()->isProduction()
        );
    }

    private function configUrls(): void
    {
        URL::forceHttps(app()->isProduction());
    }

    private function configDates(): void
    {
        Date::use(CarbonImmutable::class);
    }
}
