<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteArticleController extends Controller
{
    public function __invoke(Article $article, Request $request)
    {
        Favorite::create(['article_id' => $article->getKey(), 'user_id' => $request->user()->getKey()]);

        return response()->noContent();
    }
}
