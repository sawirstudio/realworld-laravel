<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;

class CreateArticleController extends Controller
{
    public function __invoke(Request $request)
    {
        $record = Article::create($request->validated());

        if ($tags = $request->json('tags')) {
            $tags = Tag::query()
                ->whereIn('slug', $tags)
                ->pluck('id');
            $record->tags()->attach($tags);
        }

        return $record->toResource();
    }
}
