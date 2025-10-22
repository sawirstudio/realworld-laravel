<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class DeleteCommentController extends Controller
{
    public function __invoke(Comment $comment)
    {
        $comment->deleteOrFail();

        return response()->noContent();
    }
}
