<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'password' => ['nullable', 'confirmed', 'string', 'min:8'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'url', 'max:2048'],
        ];
    }
}
