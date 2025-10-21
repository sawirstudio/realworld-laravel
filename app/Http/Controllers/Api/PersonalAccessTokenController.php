<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePersonalAccessTokenRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PersonalAccessTokenController extends Controller
{
    /**
     * @unauthenticated
     */
    public function store(CreatePersonalAccessTokenRequest $request)
    {
        $user = User::query()
            ->where('name', $request->str('name'))
            ->firstOrFail();

        if (false === Hash::check($request->str('password'), $user->password)) {
            throw ValidationException::withMessages([
                'name' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'token' => $user->createtoken('web')->plainTextToken,
        ];
    }

    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
