<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonSerializable;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    protected function successResponse(
        JsonSerializable|array|null $data = null,
        string $message = 'Berhasil.',
        int $status = Response::HTTP_OK,
        array $meta = [],
    ): JsonResponse {
        return ApiResponse::success($data, $message, $status, $meta);
    }

    protected function errorResponse(
        string $message = 'Terjadi kesalahan.',
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?array $errors = null,
        array $meta = [],
    ): JsonResponse {
        return ApiResponse::error($message, $status, $errors, $meta);
    }

    protected function authenticatedUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            throw new AuthenticationException;
        }

        return $user;
    }

    protected function revokeCurrentBearerToken(Request $request): void
    {
        $plainTextToken = $request->bearerToken();

        if ($plainTextToken === null) {
            return;
        }

        PersonalAccessToken::findToken($plainTextToken)?->delete();
    }
}
