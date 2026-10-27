<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Article\ListArticleRequest;
use App\Http\Requests\Article\SaveArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListArticleRequest $request): JsonResponse
    {
        $article = $this->paginatedArticle($request);

        return $this->successResponse(
            data: ArticleResource::collection($article->getCollection()),
            meta: $this->indexMeta($article),
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
    public function store(SaveArticleRequest $request): JsonResponse
    {

        $attributes = $request->articleAttributes();
        $attributes['slug'] = Str::slug($attributes['title']);

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('articles', 'public');
        }

        $article = Article::query()->create($attributes);

        return $this->successResponse(
            data: new ArticleResource($article),
            message: 'Artikel berhasil dibuat.',
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): JsonResponse
    {
        $next = Article::where('id', '>', $article->id)->oldest('id')->first();
        $prev = Article::where('id', '<', $article->id)->latest('id')->first();

        return $this->successResponse(
            data: new ArticleResource($article),
            meta: [
                'prev' => [
                    'id' => $prev->id ?? null,
                    'slug' => $prev->slug ?? null,
                    'title' => $prev->title ?? null,
                ],
                'next' => [
                    'id' => $next->id ?? null,
                    'slug' => $next->slug ?? null,
                    'title' => $next->title ?? null,
                ],
            ]
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
    public function update(SaveArticleRequest $request, Article $article): JsonResponse
    {
        $updateArticle = Article::findOrFail($article->id);
        $updateArticleData = $request->articleAttributes();
        $updateArticleData['slug'] = Str::slug($updateArticleData['title']);

        if ($request->hasFile('image') && $updateArticle['image'] != $request->image) {
            Storage::disk('public')->delete($updateArticle['image']);
            $updateArticleData['image'] = $request->file('image')->store('articles', 'public');
        }

        $updateArticle->update($updateArticleData);

        return $this->successResponse(
            data: new ArticleResource($article->refresh()),
            message: 'Artikel berhasil diperbarui.',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $deleteArticle = Article::find($article->id);
        Storage::disk('public')->delete($deleteArticle['image']);
        $deleteArticle->delete();

        return $this->successResponse(
            message: 'Artikel berhasil dihapus.'
        );
    }

    private function paginatedArticle(ListArticleRequest $request): LengthAwarePaginator
    {
        $query = Article::query();
        $search = $request->search();
        $sort = $request->sort();

        if ($search !== null) {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('title', 'like', '%'.$search.'%');
            });
        }

        if ($sort !== null) {
            if ($sort == 'latest') {
                $query->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('created_at', 'asc');
            }
        }

        return $query
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate($request->perPage())
            ->withQueryString();
    }

    private function indexMeta(LengthAwarePaginator $article): array
    {
        return [
            'pagination' => [
                'current_page' => $article->currentPage(),
                'per_page' => $article->perPage(),
                'last_page' => $article->lastPage(),
                'total' => $article->total(),
                'from' => $article->firstItem(),
                'to' => $article->lastItem(),
            ],
        ];
    }
}
