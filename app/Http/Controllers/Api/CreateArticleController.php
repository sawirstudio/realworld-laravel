<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;

class CreateArticleController extends Controller
{
    public function __invoke(CreateArticleRequest $request)
    {
        $record = Article::create([
            ...$request->safe()->except(['tags']),
            'user_id' => $request->user()->getKey(),
        ]);

        if ($tagsData = $request->json('tags')) {
            $tags = [];
            foreach ($tagsData as $tag) {
                $tags[] = Tag::query()
                    ->where('name', $tag)
                    ->firstOr(fn () => Tag::create(['name' => $tag]));
            }
            $record->tags()->attach($tags);
        }

        return $record->toResource();
    }
}
