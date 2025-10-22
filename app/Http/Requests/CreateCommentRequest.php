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
            'user_id' => auth()->id(),
            'article_id' => $this->route('article')->getKey(),
        ]);
    }
}
