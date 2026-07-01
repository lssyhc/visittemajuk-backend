<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\NewAccessToken;

final class RefreshTokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser($request);

        $this->revokeCurrentBearerToken($request);

        $newToken = $this->createToken($user);

        return $this->successResponse(
            data: [
                'user' => new UserResource($user),
                'token' => $newToken->plainTextToken,
            ],
            message: 'Token berhasil diperbarui.',
        );
    }

    private function createToken(User $user): NewAccessToken
    {
        return $user->createToken(
            User::API_TOKEN_NAME,
            User::API_TOKEN_ABILITIES,
            now()->addMinutes((int) config('sanctum.expiration', 1440)),
        );
    }
}
