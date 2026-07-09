<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CulinaryGalleries\SaveCulinaryGalleriesRequest;
use App\Http\Resources\CulinaryGalleriesResource;
use App\Models\CulinaryGalleries;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CulinaryGalleriesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveCulinaryGalleriesRequest $request): JsonResponse
    {
        $culinaryGalleries = $request->culinaryGalleriesAttributes();
        $culinaryGalleries['image'] = $request->file('image')->store('culinaries/galleries', 'public');

        $culinary = CulinaryGalleries::query()->create($culinaryGalleries);

        return $this->successResponse(
            data: new CulinaryGalleriesResource($culinary),
            message: 'Galeri kuliner berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(CulinaryGalleries $culinaryGalleries): JsonResponse
    {
        $culinaryGalleries = CulinaryGalleries::where('culinary_id', $culinaryGalleries->id)->get();

        return $this->successResponse(
            data: CulinaryGalleriesResource::collection($culinaryGalleries),
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CulinaryGalleries $culinaryGalleries)
    {
        $culinaryGalleries = CulinaryGalleries::find($culinaryGalleries->id);
        Storage::disk('public')->delete($culinaryGalleries['image']);
        $culinaryGalleries->delete();

        return $this->successResponse(
            message: 'Galeri kuliner berhasil dihapus.'
        );
    }
}
