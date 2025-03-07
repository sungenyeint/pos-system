<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/health_check',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->append([
            \App\Http\Middleware\LoggingInfoMiddleware::class,
        ]);
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
        // 認証されていないユーザーのリダイレクト
        $middleware->redirectGuestsTo(function (Request $request) {
            if (! $request->expectsJson()) {
                if ($request->routeIs('admin.*')) {
                    return route('admin.login');
                }
                return route('login');
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
