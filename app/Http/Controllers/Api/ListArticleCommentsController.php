<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListArticleCommentsController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(Article $article, Request $request)
    {
        return Comment::query()
            ->whereBelongsTo($article)
            ->with('user', function ($query) use ($request) {
                $query->withExists(['followers as following' => function (Builder $query) use ($request) {
                    $query->where('follower_id', $request->user('sanctum')->getKey());
                }]);
            })
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page'))
            ->withQueryString()
            ->toResourceCollection();
    }
}
