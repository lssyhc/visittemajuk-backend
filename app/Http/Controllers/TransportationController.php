<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Transportation\ListTransportationRequest;
use App\Http\Requests\Transportation\SaveTransportationRequest;
use App\Http\Resources\TransportationResource;
use App\Models\Transportation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

final class TransportationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListTransportationRequest $request): JsonResponse
    {
        $transportations = $this->paginatedTransportation($request, searchDescription: true);

        return $this->successResponse(
            data: TransportationResource::collection($transportations->getCollection()),
            meta: $this->indexMeta($transportations),
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(SaveTransportationRequest $request): JsonResponse
    {
        $attributes = $request->transportationAttributes();

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('transportations', 'public');
        }

        $transportation = Transportation::query()->create($attributes);

        return $this->successResponse(
            data: new TransportationResource($transportation),
            message: 'Transportasi berhasil dibuat',
            status: Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Transportation $transportation): JsonResponse
    {
        return $this->successResponse(
            data: new TransportationResource($transportation)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveTransportationRequest $request, Transportation $transportation): JsonResponse
    {
        $updateTransportation = Transportation::findOrFail($transportation->id);
        $attributes = $request->transportationAttributes();

        if ($request->hasFile('image') && $request->image != $updateTransportation['image']) {
            Storage::disk('public')->delete($updateTransportation['image']);
            $attributes['image'] = $request->file('image')->store('transportations', 'public');
        }

        $updateTransportation->update($attributes);

        return $this->successResponse(
            data: new TransportationResource($transportation->refresh()),
            message: 'Transportasi berhasil diperbarui'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transportation $transportation): JsonResponse
    {
        $deleteTransportation = Transportation::find($transportation->id);
        Storage::disk('public')->delete($deleteTransportation['image']);
        $deleteTransportation->delete();

        return $this->successResponse(
            message: 'Transportasi berhasil dihapus!'
        );
    }

    private function paginatedTransportation(ListTransportationRequest $request, bool $searchDescription): LengthAwarePaginator
    {
        $query = Transportation::query();
        $search = $request->search();
        $difficulty = $request->difficulty();

        if ($search !== null) {
            $query->where(function (Builder $query) use ($search, $searchDescription): void {
                $query->where('title', 'like', '%'.$search.'%');

                if ($searchDescription) {
                    $query->orWhere('description', 'like', '%'.$search.'%');
                }
            });
        }

        if ($difficulty !== null) {
            $query->where('difficulty', $difficulty);
        }

        return $query
            ->orderBy('id')
            ->with('steps:id,description,cost,duration,vehicle,transportation_id', 'tips:id,tip,transportation_id')
            ->paginate($request->perPage())
            ->withQueryString();
    }

    private function indexMeta(LengthAwarePaginator $transportation): array
    {
        return [
            'pagination' => [
                'current_page' => $transportation->currentPage(),
                'per_page' => $transportation->perPage(),
                'last_page' => $transportation->lastPage(),
                'total' => $transportation->total(),
                'from' => $transportation->firstItem(),
                'to' => $transportation->lastItem(),
            ],
            'filters' => [
                'difficulties' => Transportation::query()
                    ->select('difficulty')
                    ->distinct()
                    ->orderBy('difficulty')
                    ->pluck('difficulty')
                    ->values()
                    ->all(),
            ],
        ];
    }
}
