<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'tags' => ['array'],
            'tags.*' => ['string'],
        ];
    }

    public function passedValidation()
    {
        $this->except('tags');
    }

    public function record(): Article
    {
        return once(fn () => $this->route('article'));
    }
}
