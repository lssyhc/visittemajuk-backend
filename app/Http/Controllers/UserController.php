<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class UserController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $this->authenticatedUser($request);

        Gate::authorize('view', $user);

        return $this->successResponse(
            data: new UserResource($user),
        );
    }
}
