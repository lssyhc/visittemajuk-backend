<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Banner\SaveBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

final class BannerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->query('menu');
        if ($query) {
            $banners = Banner::where('menu', $query)->get();
        } else {
            $banners = Banner::all();
        }

        return $this->successResponse(
            data: BannerResource::collection($banners)
        );
    }

    public function store(SaveBannerRequest $request): JsonResponse
    {

        $attributes = $request->bannerAttributes();
        $attributes['image'] = $request->file('image')->store('banners', 'public');

        $banner = Banner::query()->create($attributes);

        return $this->successResponse(
            data: new BannerResource($banner),
            message: 'Banner berhasil ditambahkan.',
            status: Response::HTTP_CREATED
        );
    }

    public function update(SaveBannerRequest $request, Banner $banner): JsonResponse
    {
        $bannerUpdate = Banner::findOrFail($banner->id);

        $attributes = $request->bannerAttributes();

        if ($request->hasFile('image') && $bannerUpdate['image'] != $request->file) {
            Storage::disk('public')->delete($bannerUpdate['image']);
            $attributes['image'] = $request->file('image')->store('banners', 'public');
        }

        $bannerUpdate->update($attributes);

        return $this->successResponse(
            data: new BannerResource($bannerUpdate->refresh()),
            message: 'Banner berhasil diperbarui.',
        );
    }

    public function destroy(Banner $banner): JsonResponse
    {
        $bannerDelete = Banner::find($banner->id);
        Storage::disk('public')->delete($bannerDelete['image']);

        $bannerDelete->delete();

        return $this->successResponse(
            message: 'Banner berhasil dihapus.'
        );
    }
}
