<?php

declare(strict_types=1);

use App\Http\Middleware\ForceJsonResponse;
use App\Http\Responses\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->throttleApi();
        $middleware->api(prepend: [
            ForceJsonResponse::class,
        ]);
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e): bool {
            return $request->expectsJson() || $request->is('api/*');
        });

        $exceptions->renderable(function (AuthenticationException $e, Request $request): ?JsonResponse {
            if ($request->expectsJson() || $request->is('api/*')) {
                return ApiResponse::error('Anda belum terautentikasi.', Response::HTTP_UNAUTHORIZED);
            }

            return null;
        });

        $exceptions->renderable(function (AuthorizationException $e, Request $request): ?JsonResponse {
            if ($request->expectsJson() || $request->is('api/*')) {
                return ApiResponse::error('Anda tidak memiliki izin untuk mengakses resource ini.', Response::HTTP_FORBIDDEN);
            }

            return null;
        });

        $exceptions->renderable(function (ValidationException $e, Request $request): ?JsonResponse {
            if ($request->expectsJson() || $request->is('api/*')) {
                return ApiResponse::error(
                    message: 'Data yang diberikan tidak valid.',
                    status: Response::HTTP_UNPROCESSABLE_ENTITY,
                    errors: $e->errors(),
                );
            }

            return null;
        });

        $exceptions->renderable(function (HttpExceptionInterface $e, Request $request): ?JsonResponse {
            if ($request->expectsJson() || $request->is('api/*')) {
                $statusCode = $e->getStatusCode();
                $message = match ($statusCode) {
                    Response::HTTP_UNAUTHORIZED => 'Anda belum terautentikasi.',
                    Response::HTTP_FORBIDDEN => 'Anda tidak memiliki izin untuk mengakses resource ini.',
                    Response::HTTP_NOT_FOUND => 'Rute tidak ditemukan.',
                    Response::HTTP_METHOD_NOT_ALLOWED => 'Metode HTTP tidak diizinkan.',
                    Response::HTTP_TOO_MANY_REQUESTS => 'Terlalu banyak percobaan. Silakan coba lagi nanti.',
                    default => 'Terjadi kesalahan.',
                };

                return ApiResponse::error($message, $statusCode);
            }

            return null;
        });
    })->create();
