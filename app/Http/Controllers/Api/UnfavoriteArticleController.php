<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Favorite;
use Illuminate\Http\Request;

class UnfavoriteArticleController extends Controller
{
    public function __invoke(Article $article)
    {
        Favorite::query()
            ->whereBelongsTo($article)
            ->whereBelongsTo(auth()->user())
            ->delete();

        return response()->noContent();
    }
}
