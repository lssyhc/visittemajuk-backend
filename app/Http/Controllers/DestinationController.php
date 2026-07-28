<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Destination\CreateDestinationRequest;
use App\Http\Requests\Destination\ListDestinationRequest;
use App\Http\Requests\Destination\UpdateDestinationRequest;
use App\Http\Requests\Destination\UploadDestinationGalleryRequest;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;
use App\Models\DestinationGallery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final class DestinationController extends Controller
{
    public function index(ListDestinationRequest $request): JsonResponse
    {
        $paginator = $this->paginatedDestinations($request, searchDescription: true, eagerGalleries: true);

        return $this->successResponse(
            data: DestinationResource::collection($paginator->getCollection()),
            meta: $this->indexMeta($paginator),
        );
    }

    public function adminIndex(ListDestinationRequest $request): JsonResponse
    {
        $paginator = $this->paginatedDestinations($request, searchDescription: false, eagerGalleries: true);

        return $this->successResponse(
            data: DestinationResource::collection($paginator->getCollection()),
            meta: $this->indexMeta($paginator),
        );
    }

    public function show(Destination $destination): JsonResponse
    {
        $destination->load('galleries');

        return $this->successResponse(
            data: new DestinationResource($destination),
        );
    }

    public function adminShow(Destination $destination): JsonResponse
    {
        $destination->load('galleries');

        return $this->successResponse(
            data: new DestinationResource($destination),
        );
    }

    public function store(CreateDestinationRequest $request): JsonResponse
    {
        $attributes = $request->destinationAttributes();
        $slug = Str::slug($attributes['title']);

        if (Destination::query()->where('slug', $slug)->exists()) {
            throw ValidationException::withMessages([
                'title' => ['Nama destinasi sudah digunakan.'],
            ]);
        }

        $attributes['slug'] = $slug;
        $attributes['image'] = $request->file('image')->store('destinations', 'public');

        $destination = Destination::query()->create($attributes);

        // Handle gallery files
        $this->storeGalleryFiles($destination, $request->galleryFiles());

        $destination->load('galleries');

        return $this->successResponse(
            data: new DestinationResource($destination),
            message: 'Destinasi berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    public function update(UpdateDestinationRequest $request, Destination $destination): JsonResponse
    {
        $attributes = $request->destinationAttributes();

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($destination->image);
            $attributes['image'] = $request->file('image')->store('destinations', 'public');
        }

        $destination->update($attributes);

        // Remove gallery images that were marked for deletion
        $this->removeGalleryImages($request->removedGalleryIds());

        // Handle new gallery files
        $this->storeGalleryFiles($destination, $request->galleryFiles());

        $destination->load('galleries');

        return $this->successResponse(
            data: new DestinationResource($destination),
            message: 'Destinasi berhasil diperbarui.',
        );
    }

    public function destroy(Destination $destination): JsonResponse
    {
        $this->deleteStoredImage($destination->image);

        $destination->load('galleries');
        foreach ($destination->galleries as $gallery) {
            $this->deleteStoredImage($gallery->image);
        }

        $destination->delete();

        return $this->successResponse(
            message: 'Destinasi berhasil dihapus.',
        );
    }

    public function addGalleryImage(UploadDestinationGalleryRequest $request, Destination $destination): JsonResponse
    {
        $path = $request->file('image')->store('destinations/galleries', 'public');

        $gallery = DestinationGallery::query()->create([
            'destination_id' => $destination->id,
            'image' => $path,
            'sort_order' => (int) ($request->validated('sort_order') ?? 0),
        ]);

        return $this->successResponse(
            data: [
                'id' => $gallery->id,
                'image' => $gallery->image,
                'sortOrder' => $gallery->sort_order,
            ],
            message: 'Galeri destinasi berhasil ditambahkan.',
            status: Response::HTTP_CREATED,
        );
    }

    public function removeGalleryImage(DestinationGallery $gallery): JsonResponse
    {
        $this->deleteStoredImage($gallery->image);
        $gallery->delete();

        return $this->successResponse(
            message: 'Galeri destinasi berhasil dihapus.',
        );
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }
        if (str_starts_with($path, 'destinations/')) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Store gallery files for a destination.
     *
     * @param  array<int, UploadedFile>  $files
     */
    private function storeGalleryFiles(Destination $destination, array $files): void
    {
        $maxSortOrder = DestinationGallery::query()
            ->where('destination_id', $destination->id)
            ->max('sort_order') ?? 0;

        foreach ($files as $index => $file) {
            $path = $file->store('destinations/galleries', 'public');

            DestinationGallery::query()->create([
                'destination_id' => $destination->id,
                'image' => $path,
                'sort_order' => $maxSortOrder + $index + 1,
            ]);
        }
    }

    /**
     * Remove gallery images by their IDs.
     *
     * @param  list<int>  $galleryIds
     */
    private function removeGalleryImages(array $galleryIds): void
    {
        if (empty($galleryIds)) {
            return;
        }

        $galleries = DestinationGallery::query()
            ->whereIn('id', $galleryIds)
            ->get();

        foreach ($galleries as $gallery) {
            $this->deleteStoredImage($gallery->image);
            $gallery->delete();
        }
    }

    private function paginatedDestinations(ListDestinationRequest $request, bool $searchDescription, bool $eagerGalleries = false): LengthAwarePaginator
    {
        $query = Destination::query();
        $search = $request->search();
        $category = $request->category();

        if ($eagerGalleries) {
            $query->with('galleries');
        }

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
            ->orderBy('id', 'desc')
            ->paginate($request->perPage())
            ->withQueryString();
    }

    /**
     * @return array{
     *     pagination: array{
     *         current_page: int,
     *         per_page: int,
     *         last_page: int,
     *         total: int,
     *         from: int|null,
     *         to: int|null
     *     },
     *     filters: array{categories: list<string>}
     * }
     */
    private function indexMeta(LengthAwarePaginator $destinations): array
    {
        return [
            'pagination' => [
                'current_page' => $destinations->currentPage(),
                'per_page' => $destinations->perPage(),
                'last_page' => $destinations->lastPage(),
                'total' => $destinations->total(),
                'from' => $destinations->firstItem(),
                'to' => $destinations->lastItem(),
            ],
            'filters' => [
                'categories' => Destination::query()
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
