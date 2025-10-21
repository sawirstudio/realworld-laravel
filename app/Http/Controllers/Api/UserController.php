<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {
        return new UserResource();
    }

    public function show(User $user)
    {
        return new UserResource(
            $user
                ->load('followers'),
        );
    }

    public function edit()
    {
        return new UserResource(auth()->user());
    }

    public function update(UpdateUserRequest $request)
    {
        $user = auth()->user();
        $user->update($request->validated());

        return new UserResource($user);
    }
}
