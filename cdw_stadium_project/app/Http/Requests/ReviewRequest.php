<?php

namespace App\Http\Requests;

use App\Models\Review;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('status')) {
            $this->merge(['status' => 1]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $review = $this->route('review');
        $reviewId = $review instanceof Review ? $review->id : $review;

        return [
            'user_id' => 'required|exists:users,id',
            'court_id' => 'required|exists:courts,id',
            'booking_id' => [
                'nullable',
                'exists:bookings,id',
                Rule::unique('reviews', 'booking_id')->ignore($reviewId),
            ],
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
            'booking_id.unique' => 'Đơn đặt sân này đã có đánh giá trước đó.',
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.between' => 'Số sao đánh giá phải từ 1 đến 5.',
            'content.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự.',
        ];
    }
}


