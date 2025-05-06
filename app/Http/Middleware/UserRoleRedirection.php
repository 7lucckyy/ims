<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleRedirection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (! auth()->check()) {
            return redirect()->route('login');
        }
        $role = auth()->user()->role;

        $currentPath = explode('/', $request->path())[0];

        if ($role === $currentPath) {
            return $next($request);
        }

        $route = match ($role) {
            UserRole::Finance->value => '/finance',
            UserRole::Logistics->value => '/logistics',
            UserRole::Admin->value => '/admin',
            default => '/staff',
        };

        return redirect($route);
    }
}
