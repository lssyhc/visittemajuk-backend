<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Footer\CreateFooterSocialRequest;
use App\Http\Requests\Footer\UpdateFooterSocialRequest;
use App\Http\Resources\FooterSocialResource;
use App\Models\FooterSocial;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FooterSocialController extends Controller
{
    public function index(): JsonResponse
    {
        $socials = FooterSocial::query()
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->get();

        return $this->successResponse(
            data: FooterSocialResource::collection($socials),
        );
    }

    public function adminIndex(): JsonResponse
    {
        $socials = FooterSocial::query()
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->get();

        return $this->successResponse(
            data: FooterSocialResource::collection($socials),
        );
    }

    public function store(CreateFooterSocialRequest $request): JsonResponse
    {
        $social = FooterSocial::query()->create($request->socialAttributes());

        return $this->successResponse(
            data: new FooterSocialResource($social),
            message: 'Sosial media berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    public function update(UpdateFooterSocialRequest $request, FooterSocial $social): JsonResponse
    {
        $social->update($request->socialAttributes());

        return $this->successResponse(
            data: new FooterSocialResource($social->refresh()),
            message: 'Sosial media berhasil diperbarui.',
        );
    }

    public function destroy(FooterSocial $social): JsonResponse
    {
        $social->delete();

        return $this->successResponse(
            message: 'Sosial media berhasil dihapus.',
        );
    }
}
