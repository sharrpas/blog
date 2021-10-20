<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (auth()->user()->roles()->where('name', 'super_admin')->count()) {
            return $next($request);
        }

        if (!auth()->user()->hasPermission($permission)) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
