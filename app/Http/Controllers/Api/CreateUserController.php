<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;

class CreateUserController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(CreateUserRequest $request)
    {
        return User::create($request->validated())->toResource();
    }
}
