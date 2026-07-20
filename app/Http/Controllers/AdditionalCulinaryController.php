<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AdditionalCulinary\SaveAdditionalCulinaryRequest;
use App\Http\Resources\AdditionalCulinaryResource;
use App\Models\AdditionalCulinary;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AdditionalCulinaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $additonalCulinary = AdditionalCulinary::all();

        return $this->successResponse(
            data: AdditionalCulinaryResource::collection($additonalCulinary),
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveAdditionalCulinaryRequest $request): JsonResponse
    {
        $attributes = $request->additionalCulinaryAttributes();

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('additionalculinaries', 'public');
        }

        $additonalCulinary = AdditionalCulinary::query()->create($attributes);

        return $this->successResponse(
            data: new AdditionalCulinaryResource($additonalCulinary),
            message : 'Kuliner khas berhasil dibuat',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AdditionalCulinary $additionalCulinary): JsonResponse
    {
        return $this->successResponse(
            data: new AdditionalCulinaryResource($additionalCulinary),
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveAdditionalCulinaryRequest $request, AdditionalCulinary $additionalCulinary): JsonResponse
    {
        $additonalCulinary = AdditionalCulinary::findOrFail($additionalCulinary->id);

        $attributes = $request->additionalCulinaryAttributes();

        if ($request->hasFile('image') && $request->image != $additionalCulinary['image']) {
            Storage::disk('public')->delete($additionalCulinary['image']);
            $attributes['image'] = $request->file('image')->store('additionalculinaries', 'public');
        }

        $additionalCulinary->update($attributes);

        return $this->successResponse(
            data: new AdditionalCulinaryResource($additonalCulinary->refresh()),
            message : 'Kuliner khas berhasil diperbarui',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdditionalCulinary $additionalCulinary): JsonResponse
    {
        $deleteAdditionalCulinary = AdditionalCulinary::find($additionalCulinary->id);
        Storage::disk('public')->delete($deleteAdditionalCulinary['image']);
        $deleteAdditionalCulinary->delete();

        return $this->successResponse(
            message : 'Kuliner khas berhasil dihapus',
        );
    }
}
