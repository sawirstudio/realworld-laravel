<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class DeleteArticleController extends Controller
{
    public function __invoke(Article $article)
    {
        $article->delete();

        return response()->noContent();
    }
}
