<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role instanceof \App\Enums\Role
            ? Auth::user()->role->value
            : (string) Auth::user()->role;

        if ($userRole !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
