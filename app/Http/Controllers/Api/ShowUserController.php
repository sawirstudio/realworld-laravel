<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class ShowUserController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(User $user, Request $request)
    {
        return new UserResource(
            $user->loadExists(['followers as following' => function ($query) use ($request) {
                    $query->where('follower_id', $request->user('sanctum')?->getKey());
                }]),
        );
    }
}
