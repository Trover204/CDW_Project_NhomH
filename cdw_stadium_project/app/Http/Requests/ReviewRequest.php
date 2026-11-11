<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'court_id' => 'required|exists:courts,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'rating' => 'required|integer|between:1,5',
            'content' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Vui lòng chọn người dùng.',
            'court_id.required' => 'Vui lòng chọn sân thể thao.',
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.between' => 'Số sao đánh giá phải từ 1 đến 5.',
            'content.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự.',
        ];
    }
}

