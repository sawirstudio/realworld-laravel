<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;

class CreateCommentController extends Controller
{
    public function __invoke(CreateCommentRequest $request)
    {
        $record = Comment::create([
            ...$request->safe()->all(),
            'article_id' => $request->article()->getKey(),
            'user_id' => $request->user()->getKey(),
        ]);

        return $record->toResource();
    }
}
