<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PhotoSpot\ListPhotoSpotRequest;
use App\Http\Requests\PhotoSpot\SavePhotoSpotRequest;
use App\Http\Resources\PhotoSpotResource;
use App\Models\PhotoSpot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class PhotoSpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListPhotoSpotRequest $request): JsonResponse
    {
        $photoSpots = $this->paginatedPhotoSpots($request, searchDescription: true);

        return $this->successResponse(
            data: PhotoSpotResource::collection($photoSpots->getCollection()),
            meta: $this->indexMeta($photoSpots),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SavePhotoSpotRequest $request): JsonResponse
    {
        $attributes = $request->photoSpotAttributes();
        $attributes['slug'] = Str::slug($attributes['title']);

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('photospots', 'public');
        }

        $photoSpot = PhotoSpot::query()->create($attributes);

        return $this->successResponse(
            data: new PhotoSpotResource($photoSpot),
            message: 'Foto spot berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PhotoSpot $photoSpot): JsonResponse
    {
        return $this->successResponse(
            data: new PhotoSpotResource($photoSpot),
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SavePhotoSpotRequest $request, PhotoSpot $photoSpot): JsonResponse
    {
        $updatePhotoSpot = PhotoSpot::findOrFail($photoSpot->id);
        $updatePhotoSpotData = $request->photoSpotAttributes();
        $updatePhotoSpotData['slug'] = Str::slug($updatePhotoSpotData['title']);

        if ($request->hasFile('image') && $updatePhotoSpot['image'] != $request->image) {
            Storage::disk('public')->delete($updatePhotoSpot['image']);
            $updatePhotoSpotData['image'] = $request->file('image')->store('photospots', 'public');
        }
        $updatePhotoSpot->update($updatePhotoSpotData);

        return $this->successResponse(
            data: new PhotoSpotResource($photoSpot->refresh()),
            message: 'Foto spot berhasil diperbarui.',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PhotoSpot $photoSpot): JsonResponse
    {
        $deletePhotoSpot = PhotoSpot::find($photoSpot->id);
        Storage::disk('public')->delete($deletePhotoSpot['image']);
        $deletePhotoSpot->delete();

        return $this->successResponse(
            message: 'Foto spot berhasil dihapus.',
        );
    }

    private function paginatedPhotoSpots(ListPhotoSpotRequest $request, bool $searchDescription): LengthAwarePaginator
    {
        $query = PhotoSpot::query();
        $search = $request->search();
        $category = $request->category();

        if ($search !== null) {
            $query->where(function (Builder $query) use ($search, $searchDescription): void {
                $query->where('title', 'like', '%'.$search.'%');

                if ($searchDescription) {
                    $query->orWhere('description', 'like', '%'.$search.'%');
                }
            });
        }

        if ($category !== null) {
            $query->where('category', $category);
        }

        return $query
            ->orderBy('id')
            ->with('photoSpotGalleries:id,image,photo_spot_id')
            ->paginate($request->perPage())
            ->withQueryString();
    }

    private function indexMeta(LengthAwarePaginator $photoSpot): array
    {
        return [
            'pagination' => [
                'current_page' => $photoSpot->currentPage(),
                'per_page' => $photoSpot->perPage(),
                'last_page' => $photoSpot->lastPage(),
                'total' => $photoSpot->total(),
                'from' => $photoSpot->firstItem(),
                'to' => $photoSpot->lastItem(),
            ],
            'filters' => [
                'categories' => PhotoSpot::query()
                    ->select('category')
                    ->distinct()
                    ->orderBy('category')
                    ->pluck('category')
                    ->values()
                    ->all(),
            ],
        ];
    }
}
