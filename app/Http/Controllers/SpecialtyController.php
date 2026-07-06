<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

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
        $culinarySpecialty = $request->validate([
            'menu' => ['required'],
            'culinary_id' => ['required'],
        ]);

        $addCulinarySpecialty = Specialty::create($culinarySpecialty);

        return response()->json([
            'status' => 'success',
            'data' => $culinarySpecialty,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $culinarySpecialties = Specialty::where('culinary_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $culinarySpecialties,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialty $specialty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialtyData = $request->all();
        $specialty->update($specialtyData);

        return response()->json([
            'status' => 'success',
            'data' => $specialty,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $culinarySpecialties = Specialty::destroy($id);

        return response()->json([
            'status' => 'success',
            'data' => null,
        ], 204);
    }
}
