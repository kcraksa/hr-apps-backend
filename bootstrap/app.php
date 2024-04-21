<?php

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\DataNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(DataNotFoundException $e) {
            return ApiResponse::error("Data Not Found", 404);
        });

        $exceptions->render(function(UnauthorizedException $e) {
            return ApiResponse::error("Unauthorized", 401);
        });

        $exceptions->render(function(AuthenticationException $e) {
            return ApiResponse::error("Unauthorized", 401);
        });

        $exceptions->render(function(ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        });
    })->create();
