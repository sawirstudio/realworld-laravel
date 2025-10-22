<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Tag;

class UpdateArticleController extends Controller
{
    public function __invoke(UpdateArticleRequest $request)
    {
        $request->record()->update($request->safe()->except(['tags']));

        if ($tagsData = $request->json('tags')) {
            $tags = [];
            foreach ($tagsData as $tag) {
                $tags[] = Tag::query()
                    ->where('name', $tag)
                    ->orWhere('slug', $tag)
                    ->firstOr(fn () => Tag::create(['name' => $tag]));
            }
            $request->record()->tags()->sync($tags);
        }

        return $request->record()->toResource();
    }
}
