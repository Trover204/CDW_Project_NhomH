<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class ChatbotMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:1000'],
            'action' => ['nullable', 'string', 'in:chat,suggest_products,hot_deals,booking_help,membership_perks'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => __('Vui lòng nhập nội dung câu hỏi.'),
            'message.max' => __('Nội dung tin nhắn không được vượt quá 1000 ký tự.'),
        ];
    }
}
