<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Culinary\ListCulinaryRequest;
use App\Http\Requests\Culinary\SaveCulinaryRequest;
use App\Http\Resources\CulinaryResource;
use App\Models\Culinary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class CulinaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListCulinaryRequest $request): JsonResponse
    {
        $culinaries = $this->paginatedCulinaries($request, searchDescription: true);

        return $this->successResponse(
            data: CulinaryResource::collection($culinaries->getCollection()),
            meta: $this->indexMeta($culinaries),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveCulinaryRequest $request): JsonResponse
    {

        $attributes = $request->culinaryAttributes();
        $attributes['slug'] = Str::slug($attributes['title']);

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('culinaries', 'public');
        }

        $culinary = Culinary::query()->create($attributes);

        return $this->successResponse(
            data: new CulinaryResource($culinary),
            message: 'Kuliner berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Culinary $culinary): JsonResponse
    {
        return $this->successResponse(
            data: new CulinaryResource($culinary),
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveCulinaryRequest $request, Culinary $culinary): JsonResponse
    {
        $updateCulinary = Culinary::findOrFail($culinary->id);
        $updateCulinaryData = $request->culinaryAttributes();
        $updateCulinaryData['slug'] = Str::slug($updateCulinaryData['title']);

        if ($request->hasFile('image') && $updateCulinary['image'] != $request->image) {
            Storage::disk('public')->delete($updateCulinary['image']);
            $updateCulinaryData['image'] = $request->file('image')->store('culinaries', 'public');
        }
        $updateCulinary->update($updateCulinaryData);

        return $this->successResponse(
            data: new CulinaryResource($culinary->refresh()),
            message: 'Kuliner berhasil diperbarui.',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Culinary $culinary): JsonResponse
    {
        $deleteCulinary = Culinary::find($culinary->id);
        Storage::disk('public')->delete($deleteCulinary['image']);
        $deleteCulinary->delete();

        return $this->successResponse(
            message: 'Kuliner berhasil dihapus.',
        );
    }

    private function paginatedCulinaries(ListCulinaryRequest $request, bool $searchDescription): LengthAwarePaginator
    {
        $query = Culinary::query();
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
            ->with('specialties:id,menu,culinary_id', 'culinaryGalleries:id,image,culinary_id')
            ->paginate($request->perPage())
            ->withQueryString();
    }

    private function indexMeta(LengthAwarePaginator $culinary): array
    {
        return [
            'pagination' => [
                'current_page' => $culinary->currentPage(),
                'per_page' => $culinary->perPage(),
                'last_page' => $culinary->lastPage(),
                'total' => $culinary->total(),
                'from' => $culinary->firstItem(),
                'to' => $culinary->lastItem(),
            ],
            'filters' => [
                'categories' => Culinary::query()
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
