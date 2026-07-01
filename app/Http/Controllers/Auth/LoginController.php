<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $request->validated();
        $username = $request->string('username')->toString();
        $password = $request->string('password')->toString();

        $user = User::query()
            ->where('username', $username)
            ->first();

        if (! $user instanceof User) {
            Hash::make('timing-attack-prevention');

            return $this->invalidCredentialsResponse();
        }

        if (! Hash::check($password, $user->password)) {
            return $this->invalidCredentialsResponse();
        }

        $token = $this->createToken($user);

        return $this->successResponse(
            data: [
                'user' => new UserResource($user),
                'token' => $token->plainTextToken,
            ],
            message: 'Berhasil masuk.',
        );
    }

    private function invalidCredentialsResponse(): JsonResponse
    {
        return $this->errorResponse(
            message: 'Username atau password salah.',
            status: Response::HTTP_UNAUTHORIZED,
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
