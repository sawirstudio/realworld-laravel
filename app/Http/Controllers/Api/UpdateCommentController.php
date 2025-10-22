<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\Request;

class UpdateCommentController extends Controller
{
    public function __invoke(UpdateCommentRequest $request)
    {
        $request->record()->update($request->validated());

        return $request->record()->toResource();
    }
}
