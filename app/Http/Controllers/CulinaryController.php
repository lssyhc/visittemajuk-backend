<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Culinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CulinaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $culinary = Culinary::with('specialties:id,menu,culinary_id', 'culinaryGalleries:id,image,culinary_id')->get();

        return response()->json([
            'status' => 'success',
            'data' => $culinary,
        ], 200);
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
        $culinary = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'full_description' => ['required'],
            'category' => ['required'],
            'price' => ['required'],
            'location' => ['required'],
            'open_hours' => ['required'],
            'contact' => ['required'],
        ]);

        if ($request->location_map) {
            $culinary['location_map'] = $request->location_map;
        }

        if ($request->file('image')) {
            $culinary['image'] = $request->file('image')->store('culinaries', 'public');
        }

        $addCulinary = Culinary::create($culinary);

        return response()->json([
            'status' => 'success',
            'data' => $addCulinary,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $culinary = Culinary::where('id', $id)->with('specialties:id,menu,culinary_id', 'culinaryGalleries:id,image,culinary_id')->get()[0];

        return response()->json([
            'status' => 'success',
            'data' => $culinary,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Culinary $culinary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $culinary = Culinary::findOrFail($id);
        $updateCulinaryData = $request->all();

        if ($request->file('image') && $culinary['image'] != $request->file('image')) {
            Storage::disk('public')->delete($culinary['image']);
            $updateCulinaryData['image'] = $request->file('image')->store('culinaries', 'public');
        }
        $culinary->update($updateCulinaryData);

        return response()->json([
            'status' => 'success',
            'data' => $culinary,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $culinary = Culinary::find($id);
        Storage::disk('public')->delete($culinary['image']);
        $culinary->delete();

        return response()->json([
            'status' => 'success',
            'data' => null,
        ], 204);
    }
}
