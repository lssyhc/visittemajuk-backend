<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponse
{
    public static function success(
        JsonSerializable|array|null $data = null,
        string $message = 'Berhasil.',
        int $status = Response::HTTP_OK,
        array $meta = [],
    ): JsonResponse {
        return response()->json(self::payload(
            success: true,
            message: $message,
            data: $data,
            meta: $meta,
        ), $status);
    }

    public static function error(
        string $message = 'Terjadi kesalahan.',
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?array $errors = null,
        array $meta = [],
    ): JsonResponse {
        return response()->json(self::payload(
            success: false,
            message: $message,
            errors: $errors,
            meta: $meta,
        ), $status);
    }

    private static function payload(
        bool $success,
        string $message,
        JsonSerializable|array|null $data = null,
        ?array $errors = null,
        array $meta = [],
    ): array {
        $payload = [
            'success' => $success,
            'message' => $message,
        ];

        if ($success) {
            $payload['data'] = $data;
        }

        if (! $success) {
            $payload['errors'] = $errors;
        }

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return $payload;
    }
}
