<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CulinaryGalleries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CulinaryGalleriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $culinaryGalleries = $request->validate([
            'culinary_id' => ['required'],
        ]);
        $culinaryGalleries['image'] = $request->file('image')->store('culinaries/galleries', 'public');

        CulinaryGalleries::create($culinaryGalleries);

        return response()->json([
            'status' => 'success',
            'data' => $culinaryGalleries,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $culinaryGalleries = CulinaryGalleries::where('culinary_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $culinaryGalleries,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CulinaryGalleries $culinaryGalleries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CulinaryGalleries $culinaryGalleries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $culinaryGalleries = CulinaryGalleries::find($id);
        Storage::disk('public')->delete($culinaryGalleries['image']);
        $culinaryGalleries->delete();

        return response()->json([
            'status' => 'success',
            'data' => null,
        ], 204);
    }
}
