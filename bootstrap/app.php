<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);
        // THÊM ĐOẠN ALIAS NÀY VÀO ĐÂY
        $middleware->alias([
            'verified'     => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'check.enroll' => \App\Http\Middleware\CheckEnrollment::class,
            'course.learn' => \App\Http\Middleware\EnsureCourseLearnAccess::class,
            'admin'        => \App\Http\Middleware\CheckAdmin::class, // Đổi từ check.admin thành admin
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
