<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Review\ListReviewRequest;
use App\Http\Requests\Review\SaveReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

final class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListReviewRequest $request): JsonResponse
    {
        $reviews = $this->paginatedReviews($request, searchText : true);

        return $this->successResponse(
            data: ReviewResource::collection($reviews->getCollection()),
            meta: $this->indexMeta($reviews),
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
    public function store(SaveReviewRequest $request)
    {
        $attributes = $request->reviewAttributes();

        $review = Review::query()->create($attributes);

        return $this->successResponse(
            data: new ReviewResource($review),
            message: 'Review berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    private function paginatedReviews(ListReviewRequest $request, bool $searchText): LengthAwarePaginator
    {
        $query = Review::query();
        $search = $request->search();
        $destination = (int) $request->destination();
        $rate = (int) $request->rate();

        if ($search !== null) {
            $query->where(function (Builder $query) use ($search, $searchText): void {
                $query->where('name', 'like', '%'.$search.'%');

                if ($searchText) {
                    $query->orWhere('text', 'like', '%'.$search.'%');
                }
            });
        }

        if ($destination !== 0) {
            $query->where('destination_id', $destination);
        }

        if ($rate !== 0) {
            $query->where('rating', $rate);
        }

        return $query
            ->orderBy('id')
            ->with('destination:id,title')
            ->paginate($request->perPage())
            ->withQueryString();
    }

    private function indexMeta(LengthAwarePaginator $reviews): array
    {
        return [
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'per_page' => $reviews->perPage(),
                'last_page' => $reviews->lastPage(),
                'total' => $reviews->total(),
                'from' => $reviews->firstItem(),
                'to' => $reviews->lastItem(),
            ],
        ];
    }
}
