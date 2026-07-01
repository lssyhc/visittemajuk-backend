<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $this->authenticatedUser($request);

        $this->revokeCurrentBearerToken($request);

        return $this->successResponse(
            message: 'Berhasil keluar.',
        );
    }
}
