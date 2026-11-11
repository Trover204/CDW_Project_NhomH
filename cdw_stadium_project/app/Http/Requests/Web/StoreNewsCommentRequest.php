<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('rating')) {
            $this->merge(['rating' => 5]);
        }
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:500'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Vui lòng nhập nội dung bình luận.',
            'content.max' => 'Nội dung bình luận không được vượt quá 500 ký tự.',
            'rating.between' => 'Số sao đánh giá phải từ 1 đến 5.',
        ];
    }
}
