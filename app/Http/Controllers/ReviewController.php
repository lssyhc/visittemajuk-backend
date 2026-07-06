<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ulasan = Review::with('destination:id,title')
            ->get()
            ->makeHidden('destination_id');

        return response()->json([
            'status' => 'success',
            'data' => $ulasan,
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
        $ulasan = $request->validate([
            'name' => ['required'],
            'text' => ['required'],
            'destination_id' => ['required'],
            'rating' => ['required'],
        ]);

        $inserted_data = Review::create($ulasan);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.',
            'data' => $inserted_data,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
