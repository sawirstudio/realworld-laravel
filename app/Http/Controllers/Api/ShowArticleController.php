<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShowArticleController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(Article $article, Request $request)
    {
        return $article
            ->loadExists(['favorites as favorited' => function ($query) use ($request) {
                $query->where('user_id', $request->user('sanctum')?->getKey());
            }])
            ->load(['user' => function ($query) use ($request) {
                $query->withCount('followers')
                    ->withExists(['followers as following' => function (Builder $query) use ($request) {
                        $query->where('follower_id', $request->user('sanctum')?->getKey());
                    }]);
            }])
            ->toResource();
    }
}
