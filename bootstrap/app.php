<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/login',
            users: function (Request $request): string {
                $user = $request->user();
                if ($user?->isAdmin()) {
                    return route('admin.dashboard');
                }
                if ($user?->isDosen()) {
                    return route('dosen.dashboard');
                }
                if ($user?->isMahasiswa()) {
                    return route('mahasiswa.dashboard');
                }

                // If user is authenticated but role is invalid or unset, clear session to prevent redirect loop
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return route('login');
            }
        );

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
