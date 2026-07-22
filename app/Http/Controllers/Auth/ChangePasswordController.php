<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

final class ChangePasswordController extends Controller
{
    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $user = $this->authenticatedUser($request);

        if (! Hash::check((string) $request->validated('current_password'), $user->password)) {
            return $this->errorResponse(
                message: 'Password lama salah.',
                status: Response::HTTP_UNPROCESSABLE_ENTITY,
                errors: ['current_password' => ['Password lama salah.']],
            );
        }

        $user->password = (string) $request->validated('password');
        $user->save();

        return $this->successResponse(
            message: 'Password berhasil diperbarui.',
        );
    }
}
