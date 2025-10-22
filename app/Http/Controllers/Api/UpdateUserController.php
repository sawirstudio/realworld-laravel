<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UpdateUserController extends Controller
{
    public function __invoke(UpdateUserRequest $request)
    {
        $request->user()->update($request->validated());

        return response()->noContent();
    }
}
