<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;

class CreateCommentController extends Controller
{
    public function __invoke(CreateCommentRequest $request)
    {
        $record = Comment::create($request->validated());

        return $record->toResource();
    }
}
