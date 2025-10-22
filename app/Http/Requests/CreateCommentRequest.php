<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:1000'],
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            'user_id' => $this->user()->getKey(),
            'article_id' => $this->article()->getKey(),
        ]);
    }

    public function article(): Article
    {
        return once(fn () => $this->route('article'));
    }
}
