<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Daftarkan middleware Spatie Permission
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Redirect setelah login berdasarkan role
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            if (!$user)
                return '/login';

            if ($user->hasRole('admin')) {
                return '/admin/dashboard';
            } elseif ($user->hasRole('anggota')) {
                return '/anggota/dashboard';
            } elseif ($user->hasRole('siswa_baru')) {
                return '/siswa/seragam/tagihan';
            }

            return '/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );
    })->create();