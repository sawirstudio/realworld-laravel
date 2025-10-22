<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;

class UnfollowUserController extends Controller
{
    public function __invoke(User $user)
    {
        Follower::query()
            ->where('user_id', $user->getKey())
            ->where('follower_id', auth()->id())
            ->delete();

        return response()->noContent();
    }
}
