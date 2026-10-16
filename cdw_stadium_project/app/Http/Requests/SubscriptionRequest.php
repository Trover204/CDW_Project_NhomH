<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'status' => 'boolean',
            'payment_method' => 'nullable|string|max:191',
            'amount_paid' => 'nullable|numeric|min:0',
            'transaction_code' => 'nullable|string|max:191|unique:subscriptions,transaction_code,' . ($this->route('subscription') ? $this->route('subscription')->id : 'NULL'),
        ];
    }
}
