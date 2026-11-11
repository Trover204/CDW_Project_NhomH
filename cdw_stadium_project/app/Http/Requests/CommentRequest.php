<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('status')) {
            $rawStatus = $this->input('status');
            if ($rawStatus === 'approved' || $rawStatus === '1' || $rawStatus === 1 || $rawStatus === true || $rawStatus === 'true') {
                $this->merge(['status' => 1]);
            } elseif ($rawStatus === 'pending' || $rawStatus === 'hidden' || $rawStatus === '0' || $rawStatus === 0 || $rawStatus === false || $rawStatus === 'false') {
                $this->merge(['status' => 0]);
            }
        }

        if (! $this->filled('rating')) {
            $this->merge(['rating' => 5]);
        }
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'news_id' => 'required|exists:news,id',
            'content' => 'required|string|max:1000',
            'rating' => 'nullable|integer|between:1,5',
            'status' => 'required|boolean',
            'version' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Vui lòng chọn người dùng.',
            'user_id.exists' => 'Người dùng đã chọn không hợp lệ.',
            'news_id.required' => 'Vui lòng chọn bài viết tin tức.',
            'news_id.exists' => 'Bài viết tin tức đã chọn không tồn tại.',
            'content.required' => 'Vui lòng nhập nội dung bình luận.',
            'content.max' => 'Nội dung bình luận không được vượt quá 1000 ký tự.',
            'rating.between' => 'Số sao phải từ 1 đến 5.',
            'status.required' => 'Vui lòng chọn trạng thái hiển thị.',
        ];
    }
}

