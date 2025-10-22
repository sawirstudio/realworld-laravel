<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * @unauthenticated
     */
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
        $record = Article::create($request->validated());
        if ($tags = $request->json('tags')) {
            $tags = Tag::query()
                ->whereIn('slug', $tags)
                ->pluck('id');
            $record->tags()->attach($tags);
        }
        return new ArticleResource($record);
    }

    /**
     * @unauthenticated
     */
    public function show(Article $article)
    {
        return new ArticleResource(
            $article
                ->when(auth('sanctum')->check(), function ($query) {
                    $query->withExists(['favorites as favorited' => function ($query) {
                        $query->where('user_id', auth('sanctum')->id());
                    }]);
                })
                ->loadMissing(['user' => function (Builder $query) {
                    $query->withCount('followers');
                    $query->withExists(['followers as following' => function ($query) {
                        $query->where('follower_id', auth('sanctum')->id());
                    }]);
                }]),
        );
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $record->update($request->validated());

        if ($tags = $request->json('tags')) {
            $tags = Tag::query()
                ->whereIn('slug', $tags)
                ->pluck('id');
            $record->tags()->sync($tags);
        }

        $record->setRelation('user', auth()->user());

        return new ArticleResource($record);
    }
}
