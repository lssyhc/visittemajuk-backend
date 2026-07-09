<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Destination\ListDestinationRequest;
use App\Http\Requests\Destination\SaveDestinationRequest;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final class DestinationController extends Controller
{
    public function index(ListDestinationRequest $request): JsonResponse
    {
        $destinations = $this->paginatedDestinations($request, searchDescription: true);

        return $this->successResponse(
            data: DestinationResource::collection($destinations->getCollection()),
            meta: $this->indexMeta($destinations),
        );
    }

    public function adminIndex(ListDestinationRequest $request): JsonResponse
    {
        $destinations = $this->paginatedDestinations($request, searchDescription: false);

        return $this->successResponse(
            data: DestinationResource::collection($destinations->getCollection()),
            meta: $this->indexMeta($destinations),
        );
    }

    public function show(Destination $destination): JsonResponse
    {
        return $this->successResponse(
            data: new DestinationResource($destination),
        );
    }

    public function store(SaveDestinationRequest $request): JsonResponse
    {
        $attributes = $request->destinationAttributes();
        $slug = Str::slug($attributes['title']);

        if (Destination::query()->where('slug', $slug)->exists()) {
            throw ValidationException::withMessages([
                'title' => ['Nama destinasi sudah digunakan.'],
            ]);
        }

        $attributes['slug'] = $slug;

        $destination = Destination::query()->create($attributes);

        return $this->successResponse(
            data: new DestinationResource($destination),
            message: 'Destinasi berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    public function update(SaveDestinationRequest $request, Destination $destination): JsonResponse
    {
        $destination->update($request->destinationAttributes());

        return $this->successResponse(
            data: new DestinationResource($destination->refresh()),
            message: 'Destinasi berhasil diperbarui.',
        );
    }

    public function destroy(Destination $destination): JsonResponse
    {
        $destination->delete();

        return $this->successResponse(
            message: 'Destinasi berhasil dihapus.',
        );
    }

    private function paginatedDestinations(ListDestinationRequest $request, bool $searchDescription): LengthAwarePaginator
    {
        $query = Destination::query();
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
