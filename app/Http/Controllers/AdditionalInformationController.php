<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AdditionalInformation\SaveAdditionalInformationRequest;
use App\Http\Resources\AdditionalInformationResource;
use App\Models\AdditionalInformation;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AdditionalInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $additionalInformation = AdditionalInformation::all();

        return $this->successResponse(
            data: AdditionalInformationResource::collection($additionalInformation),
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
    public function store(SaveAdditionalInformationRequest $request): JsonResponse
    {
        $attributes = $request->additionalInformationAttributes();

        $additonalInformation = AdditionalInformation::query()->create($attributes);

        return $this->successResponse(
            data: new AdditionalInformationResource($additonalInformation),
            message : 'Informasi tambahan berhasil dibuat',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AdditionalInformation $additionalInformation): JsonResponse
    {
        return $this->successResponse(
            data: new AdditionalInformationResource($additionalInformation),
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
    public function update(SaveAdditionalInformationRequest $request, AdditionalInformation $additionalInformation): JsonResponse
    {
        $attributes = $request->additionalInformationAttributes();
        $additionalInformation->update($attributes);

        return $this->successResponse(
            data: new AdditionalInformationResource($additionalInformation->refresh()),
            message : 'Informasi tambahan berhasil diperbarui',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdditionalInformation $additionalInformation): JsonResponse
    {
        $additionalInformation->delete();

        return $this->successResponse(
            message : 'Informasi tambahan berhasil dihapus',
        );
    }
}
