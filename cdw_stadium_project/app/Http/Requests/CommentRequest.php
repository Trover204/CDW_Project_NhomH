<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
  public function authorize(): bool
{
    return true;   // mặc định là false, nhớ đổi
}

public function rules(): array
{
    return [
        'user_id'  => 'required|exists:users,id',
        'court_id' => 'required|exists:courts,id',
        'content'  => 'required|string|max:1000',
        'rating'   => 'nullable|integer|between:1,5',
        'status'   => 'required|in:pending,approved,hidden',
    ];
}

public function messages(): array
{
    return [
        'content.required' => 'Vui lòng nhập nội dung bình luận.',
        'rating.between'   => 'Số sao phải từ 1 đến 5.',
    ];
}
}

