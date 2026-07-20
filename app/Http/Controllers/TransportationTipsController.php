<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TransportationTips\SaveTransportationTipsRequest;
use App\Http\Resources\TransportationTipsResource;
use App\Models\TransportationTips;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransportationTipsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveTransportationTipsRequest $request): JsonResponse
    {
        $attributes = $request->transportationTipsAttributes();
        $transportationTips = TransportationTips::query()->create($attributes);

        return $this->successResponse(
            data: new TransportationTipsResource($transportationTips),
            message: 'Tips transportasi berhasil ditambahkan',
            status: Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveTransportationTipsRequest $request, TransportationTips $transportationTips): JsonResponse
    {
        $attributes = $request->transportationTipsAttributes();
        $transportationTips->update($attributes);

        return $this->successResponse(
            data: new TransportationTipsResource($transportationTips->refresh()),
            message: 'Tips transportasi berhasil diperbarui'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransportationTips $transportationTips): JsonResponse
    {
        $transportationTips->delete();

        return $this->successResponse(
            message: 'Tips transportasi berhasil dihapus'
        );
    }
}
