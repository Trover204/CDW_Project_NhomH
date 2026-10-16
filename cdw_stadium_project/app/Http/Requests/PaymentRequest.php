<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentId = $this->route('payment') ? $this->route('payment')->id : null;

        return [
            'booking_id' => 'required|exists:bookings,id|unique:payments,booking_id,'.$paymentId,
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:191',
            'transaction_code' => 'nullable|string|max:191|unique:payments,transaction_code,'.$paymentId,
            'status' => 'required|in:pending,paid,failed,refunded',
            'paid_at' => 'nullable|date',
        ];
    }
}
