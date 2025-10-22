<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FollowUserController extends Controller
{
    public function __invoke(User $user)
    {
        $user->followers()->create(['follower_id' => auth()->id()]);

        return response()->noContent();
    }
}
