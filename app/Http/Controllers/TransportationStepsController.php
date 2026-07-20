<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TransportationSteps\SaveTransportationStepsRequest;
use App\Http\Resources\TransportationStepsResource;
use App\Models\TransportationSteps;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TransportationStepsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveTransportationStepsRequest $request): JsonResponse
    {
        $attributes = $request->transportationStepsAttributes();
        $transportationSteps = TransportationSteps::query()->create($attributes);

        return $this->successResponse(
            data: new TransportationStepsResource($transportationSteps),
            message : 'Langkah transportasi berhasil ditambahkan',
            status: Response::HTTP_CREATED
        );
    }

    public function update(SaveTransportationStepsRequest $request, TransportationSteps $transportationSteps): JsonResponse
    {
        $attributes = $request->transportationStepsAttributes();
        $transportationSteps->update($attributes);

        return $this->successResponse(
            data: new TransportationStepsResource($transportationSteps->refresh()),
            message: 'Langkah transportasi berhasil diperbarui'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransportationSteps $transportationSteps): JsonResponse
    {
        $transportationSteps->delete();

        return $this->successResponse(
            message: 'Langkah transportasi berhasil dihapus'
        );
    }
}
