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
    public function __invoke(User $user)
    {
        return new UserResource(
            $user->loadExists(['followers as following' => function ($query) {
                    $query->where('follower_id', auth('sanctum')->id());
                }]),
        );
    }
}
