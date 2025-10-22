<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;

class FollowUserController extends Controller
{
    public function __invoke(User $user)
    {
        Follower::create([
            'user_id' => $user->getKey(),
            'follower_id' => auth()->id(),
        ]);

        return response()->noContent();
    }
}
