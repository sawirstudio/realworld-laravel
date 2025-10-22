<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('article')->user_id == $this->user()->getKey();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'tags' => ['array'],
            'tags.*' => ['string', Rule::exists(Tag::class, 'slug')],
        ];
    }

    public function passedValidation()
    {
        $this->except('tags');
    }
}
