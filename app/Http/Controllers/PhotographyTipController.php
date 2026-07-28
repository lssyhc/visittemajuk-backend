<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PhotographyTip\CreatePhotographyTipRequest;
use App\Http\Requests\PhotographyTip\UpdatePhotographyTipRequest;
use App\Http\Resources\PhotographyTipResource;
use App\Models\PhotographyTip;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

final class PhotographyTipController extends Controller
{
    public function index(): JsonResponse
    {
        $tips = PhotographyTip::query()
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->get();

        return $this->successResponse(
            data: PhotographyTipResource::collection($tips),
        );
    }

    public function adminIndex(): JsonResponse
    {
        $tips = PhotographyTip::query()
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->get();

        return $this->successResponse(
            data: PhotographyTipResource::collection($tips),
        );
    }

    public function store(CreatePhotographyTipRequest $request): JsonResponse
    {
        $attributes = $request->tipAttributes();

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('photography-tips', 'public');
        }

        $tip = PhotographyTip::query()->create($attributes);

        return $this->successResponse(
            data: new PhotographyTipResource($tip),
            message: 'Tips fotografi berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    public function update(UpdatePhotographyTipRequest $request, PhotographyTip $tip): JsonResponse
    {
        $attributes = $request->tipAttributes();

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($tip->image);
            $attributes['image'] = $request->file('image')->store('photography-tips', 'public');
        }

        $tip->update($attributes);

        return $this->successResponse(
            data: new PhotographyTipResource($tip->refresh()),
            message: 'Tips fotografi berhasil diperbarui.',
        );
    }

    public function destroy(PhotographyTip $tip): JsonResponse
    {
        $this->deleteStoredImage($tip->image);
        $tip->delete();

        return $this->successResponse(
            message: 'Tips fotografi berhasil dihapus.',
        );
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }
        if (str_starts_with($path, 'photography-tips/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
