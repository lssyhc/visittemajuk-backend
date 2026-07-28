<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AccomodationGalleries\SaveAccomodationGalleriesRequest;
use App\Http\Resources\AccomodationGalleriesResource;
use App\Models\AccomodationGalleries;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

final class AccomodationGalleriesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveAccomodationGalleriesRequest $request): JsonResponse
    {
        $accomodationGalleries = $request->accomodationGalleriesAttributes();
        $accomodationGalleries['image'] = $request->hasFile('image')
            ? $request->file('image')->store('accomodations/galleries', 'public')
            : $accomodationGalleries['image'];

        $gallery = AccomodationGalleries::query()->create($accomodationGalleries);

        return $this->successResponse(
            data: new AccomodationGalleriesResource($gallery),
            message: 'Galeri akomodasi berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AccomodationGalleries $accomodationGalleries): JsonResponse
    {
        $galleries = AccomodationGalleries::query()
            ->where('accomodation_id', $accomodationGalleries->accomodation_id)
            ->get();

        return $this->successResponse(
            data: AccomodationGalleriesResource::collection($galleries),
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccomodationGalleries $accomodationGalleries): JsonResponse
    {
        $accomodationGalleries = AccomodationGalleries::find($accomodationGalleries->id);
        Storage::disk('public')->delete($accomodationGalleries['image']);
        $accomodationGalleries->delete();

        return $this->successResponse(
            message: 'Galeri akomodasi berhasil dihapus.'
        );
    }
}
