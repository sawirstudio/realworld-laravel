<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateArticleRequest;

class UpdateArticleController extends Controller
{
    public function __invoke(UpdateArticleRequest $request)
    {
        $request->record()->update($request->validated());

        return $request->record()->toResource();
    }
}
