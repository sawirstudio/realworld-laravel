<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        return ArticleResource::collection(
            Article::query()
                ->when($request->query('tag'), function ($query, $tag) {
                    $query->whereRelation('tags', 'slug', $tag);
                })
                ->when($request->query('author'), function ($query, $author) {
                    $query->whereRelation('user', 'name', $author);
                })
                ->when($request->filled('favorited'), function ($query) {
                    $query->whereRelation('favorites', 'user_id', auth('sanctum')->id());
                })
                ->when($request->filled('favorited'), function ($query) {
                    $query->whereRelation('favorites', 'user_id', auth('sanctum')->id());
                })
                ->when($request->filled('feed'), function ($query) {
                    $query->whereIn('user_id', User::query()
                        ->whereRelation('followers', 'follower_id', auth('sanctum')->id())
                        ->select('id'));
                })
                ->with('user.followers')
                ->withCount('favorites')
                ->withCount('comments')
                ->orderByDesc('created_at')
                ->paginate($request->query('per_page'))
                ->withQueryString(),
        );
    }

    public function store(CreateArticleRequest $request)
    {
        $record = Article::create([
            'title' => $request->str('title'),
            'excerpt' => $request->str('excerpt'),
            'content' => $request->str('content'),
            'user_id' => auth()->id(),
        ]);

        return $record;
    }

    public function show(Article $article)
    {
        return new ArticleResource(
            $article
                ->loadMissing('user.followers'),
        );
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update([
            'title' => $request->str('title'),
            'excerpt' => $request->str('excerpt'),
            'content' => $request->str('content'),
        ]);

        return new ArticleResource($article);
    }
}
