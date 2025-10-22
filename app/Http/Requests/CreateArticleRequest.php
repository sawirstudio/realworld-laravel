<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['string', 'max:1000'],
            'content' => ['string'],
            'tags' => ['array'],
            'tags.*' => ['string'],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'user_id' => $this->user()->getKey(),
        ])
        ->except('tags');
    }
}
