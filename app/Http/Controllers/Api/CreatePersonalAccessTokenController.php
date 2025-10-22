<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePersonalAccessTokenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CreatePersonalAccessTokenController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke(CreatePersonalAccessTokenRequest $request)
    {
        $user = User::query()
            ->where('name', $request->safe()->input('name'))
            ->firstOrFail();

        if (false === Hash::check($request->safe()->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'name' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'token' => $user->createtoken('web')->plainTextToken,
        ];
    }
}
