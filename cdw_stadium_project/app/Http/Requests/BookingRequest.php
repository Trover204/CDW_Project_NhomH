<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'court_id' => 'required|exists:courts,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'customer_name' => 'required|string|max:50',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:191',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'subtotal' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,checked_in,completed,cancelled,rejected',
        ];
    }
}
