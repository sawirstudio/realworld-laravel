<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @unauthenticated
     */
    public function index(Request $request, Article $article)
    {
        return CommentResource::collection(
            Comment::query()
                ->whereBelongsTo($article)
                ->with('user', function ($query) {
                    $query->withExists(['followers as following' => function (Builder $query) {
                        $query->where('follower_id', auth('sanctum')->id());
                    }]);
                })
                ->orderByDesc('created_at')
                ->paginate($request->query('per_page'))
                ->withQueryString(),
        );
    }

    public function store(CreateCommentRequest $request)
    {
        return new CommentResource(
            Comment::create($request->validated())
                ->load('user'),
        );
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return new CommentResource(
            $comment->load('user'),
        );
    }
}
