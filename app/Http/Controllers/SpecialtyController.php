<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Specialty\SaveSpecialtyRequest;
use App\Http\Resources\SpecialtyResource;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SpecialtyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveSpecialtyRequest $request): JsonResponse
    {
        $specialty = $request->specialtyAttributes();

        $addCulinarySpecialty = Specialty::query()->create($specialty);

        return $this->successResponse(
            data: new SpecialtyResource($addCulinarySpecialty),
            message: 'Spesialisasi berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveSpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->specialtyAttributes());

        return $this->successResponse(
            data: new SpecialtyResource($specialty->refresh()),
            message: 'Data Spesialisasi berhasil diperbarui.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty)
    {
        $specialty->delete();

        return $this->successResponse(
            message: 'Spesialisasi berhasil dihapus.',
        );
    }
}
