<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, Article $article)
    {
        return CommentResource::collection(
            Comment::query()
                ->whereBelongsTo($article)
                ->with('user')
                ->orderByDesc('created_at')
                ->paginate($request->query('per_page'))
                ->withQueryString(),
        );
    }

    public function store(CreateCommentRequest $request, Article $article)
    {
        $comment = Comment::create([
            'content' => $request->json('content'),
            'user_id' => auth()->id(),
            'article_id' => $article->getKey(),
        ]);

        return new CommentResource(
            $comment->load('user'),
        );
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update([
            'content' => $request->str('content'),
        ]);

        return new CommentResource(
            $comment->load('user'),
        );
    }
}
