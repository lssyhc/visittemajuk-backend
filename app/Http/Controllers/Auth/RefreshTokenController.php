<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class RefreshTokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser($request);

        $this->revokeCurrentBearerToken($request);

        $newToken = $user->createApiToken();

        return $this->successResponse(
            data: [
                'user' => new UserResource($user),
                'token' => $newToken->plainTextToken,
            ],
            message: 'Token berhasil diperbarui.',
        );
    }
}
