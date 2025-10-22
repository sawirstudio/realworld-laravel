<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ListArticlesController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(Request $request)
    {
        return Article::query()
            ->when($request->query('tag'), function ($query, $tag) {
                $query->whereRelation('tags', 'name', $tag);
            })
            ->when($request->query('user'), function ($query, $author) {
                $query->whereRelation('user', 'name', $author);
            })
            ->when($request->filled('favorited'), function ($query) use ($request) {
                $query->whereRelation('favorites', 'user_id', operator: $request->user('sanctum')?->getKey());
            })
            ->when($request->filled('favorited'), function ($query) use ($request) {
                $query->whereRelation('favorites', 'user_id', $request->user('sanctum')?->getKey());
            })
            ->when($request->filled('feed'), function ($query) use ($request) {
                $query->whereIn('user_id', User::query()
                    ->whereRelation('followers', 'follower_id', $request->user('sanctum')?->getKey())
                    ->select('id'));
            })
            ->with('user.followers')
            ->withCount('favorites')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page'))
            ->withQueryString()
            ->toResourceCollection();
    }
}
