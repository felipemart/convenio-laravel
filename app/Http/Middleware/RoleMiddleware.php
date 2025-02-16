<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $role = explode('|', $role);

        if (static::checkRole('hasRole', $role)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }

    public static function checkRole($method, $role, $guard = null): bool
    {
        return auth($guard)->check() && auth($guard)->user()->{$method}($role);
    }
}
