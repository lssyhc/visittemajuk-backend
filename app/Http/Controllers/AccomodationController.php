<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Accomodation\ListAccomodationRequest;
use App\Http\Requests\Accomodation\SaveAccomodationRequest;
use App\Http\Requests\Accomodation\UpdateAccomodationRequest;
use App\Http\Resources\AccomodationResource;
use App\Models\Accomodation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final class AccomodationController extends Controller
{
    public function index(ListAccomodationRequest $request): JsonResponse
    {
        $accomodations = $this->paginatedAccomodations($request, searchDescription: true);

        return $this->successResponse(
            data: AccomodationResource::collection($accomodations->getCollection()),
            meta: $this->indexMeta($accomodations),
        );
    }

    public function adminIndex(ListAccomodationRequest $request): JsonResponse
    {
        $accomodations = $this->paginatedAccomodations($request, searchDescription: false);

        return $this->successResponse(
            data: AccomodationResource::collection($accomodations->getCollection()),
            meta: $this->indexMeta($accomodations),
        );
    }

    public function show(Accomodation $accomodation): JsonResponse
    {
        return $this->successResponse(
            data: new AccomodationResource($accomodation->load('roomTypes', 'accomodationGalleries')),
        );
    }

    public function store(SaveAccomodationRequest $request): JsonResponse
    {
        $attributes = $request->accomodationAttributes();
        $slug = Str::slug($attributes['title']);

        if (Accomodation::query()->where('slug', $slug)->exists()) {
            throw ValidationException::withMessages([
                'title' => ['Nama akomodasi sudah digunakan.'],
            ]);
        }

        $attributes['slug'] = $slug;

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('accomodations', 'public');
        }

        $accomodation = Accomodation::query()->create($attributes);

        foreach ($request->roomTypeAttributes() as $roomType) {
            $accomodation->roomTypes()->create($roomType);
        }

        return $this->successResponse(
            data: new AccomodationResource($accomodation->load('roomTypes', 'accomodationGalleries')),
            message: 'Akomodasi berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    public function update(UpdateAccomodationRequest $request, Accomodation $accomodation): JsonResponse
    {
        $updateAccomodation = Accomodation::findOrFail($accomodation->id);
        $updateAccomodationData = $request->accomodationAttributes();

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($updateAccomodation->image);
            $updateAccomodationData['image'] = $request->file('image')->store('accomodations', 'public');
        } else {
            $updateAccomodationData['image'] = $updateAccomodation->image;
        }

        $updateAccomodation->update($updateAccomodationData);

        $this->syncRoomTypes($accomodation, $request->roomTypeAttributes());

        return $this->successResponse(
            data: new AccomodationResource($accomodation->refresh()->load('roomTypes', 'accomodationGalleries')),
            message: 'Akomodasi berhasil diperbarui.',
        );
    }

    public function destroy(Accomodation $accomodation): JsonResponse
    {
        Storage::disk('public')->delete($accomodation->image);
        $accomodation->delete();

        return $this->successResponse(
            message: 'Akomodasi berhasil dihapus.',
        );
    }

    private function paginatedAccomodations(ListAccomodationRequest $request, bool $searchDescription): LengthAwarePaginator
    {
        $query = Accomodation::query()->with('roomTypes', 'accomodationGalleries');
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
            ->orderBy('id', 'desc')
            ->paginate($request->perPage())
            ->withQueryString();
    }

    /**
     * Sync room types: delete removed entries and upsert the rest.
     *
     * @param  list<array{name: string, description: string, capacity: int, price: float}>  $roomTypes
     */
    private function syncRoomTypes(Accomodation $accomodation, array $roomTypes): void
    {
        $incomingNames = array_column($roomTypes, 'name');

        // Delete room types that were removed from the request.
        $accomodation->roomTypes()
            ->whereNotIn('name', $incomingNames)
            ->delete();

        foreach ($roomTypes as $roomType) {
            $accomodation->roomTypes()->updateOrCreate(
                ['name' => $roomType['name']],
                $roomType,
            );
        }
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
    private function indexMeta(LengthAwarePaginator $accomodations): array
    {
        return [
            'pagination' => [
                'current_page' => $accomodations->currentPage(),
                'per_page' => $accomodations->perPage(),
                'last_page' => $accomodations->lastPage(),
                'total' => $accomodations->total(),
                'from' => $accomodations->firstItem(),
                'to' => $accomodations->lastItem(),
            ],
            'filters' => [
                'categories' => Accomodation::query()
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
