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
    /**
     * @unauthenticated
     */
    public function store(CreateUserRequest $request)
    {
        return new UserResource(
            User::create($request->validated())
        );
    }

    /**
     * @unauthenticated
     */
    public function show(User $user)
    {
        return new UserResource(
            $user->loadExists(['followers as following' => function ($query) {
                    $query->where('follower_id', auth('sanctum')->id());
                }]),
        );
    }

    public function edit()
    {
        return new UserResource(auth()->user());
    }

    public function update(UpdateUserRequest $request)
    {
        auth()->user()->update($request->validated());

        return new UserResource(auth()->user());
    }
}
