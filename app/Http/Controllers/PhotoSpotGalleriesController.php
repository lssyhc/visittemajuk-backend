<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PhotoSpotGalleries\SavePhotoSpotGalleriesRequest;
use App\Http\Resources\PhotoSpotGalleriesResource;
use App\Models\PhotoSpotGalleries;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

final class PhotoSpotGalleriesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SavePhotoSpotGalleriesRequest $request): JsonResponse
    {
        $photoSpotGalleries = $request->photoSpotGalleriesAttributes();
        $photoSpotGalleries['image'] = $request->file('image')->store('photospots/galleries', 'public');

        $gallery = PhotoSpotGalleries::query()->create($photoSpotGalleries);

        return $this->successResponse(
            data: new PhotoSpotGalleriesResource($gallery),
            message: 'Galeri foto spot berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PhotoSpotGalleries $photoSpotGalleries): JsonResponse
    {
        $galleries = PhotoSpotGalleries::query()
            ->where('photo_spot_id', $photoSpotGalleries->photo_spot_id)
            ->get();

        return $this->successResponse(
            data: PhotoSpotGalleriesResource::collection($galleries),
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PhotoSpotGalleries $photoSpotGalleries): JsonResponse
    {
        $photoSpotGalleries = PhotoSpotGalleries::find($photoSpotGalleries->id);
        Storage::disk('public')->delete($photoSpotGalleries['image']);
        $photoSpotGalleries->delete();

        return $this->successResponse(
            message: 'Galeri foto spot berhasil dihapus.'
        );
    }
}
