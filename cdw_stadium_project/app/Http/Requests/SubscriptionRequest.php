<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('version')) {
            $subscription = $this->route('subscription');
            $this->merge([
                'version' => $subscription instanceof \App\Models\Subscription ? (int) $subscription->version : 0,
            ]);
        }
    }

    public function rules(): array
    {
        $subscription = $this->route('subscription');
        $paymentId = $subscription instanceof \App\Models\Subscription
            ? $subscription->payment?->id
            : null;

        return [
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'status' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'transaction_code' => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('payments', 'transaction_code')->ignore($paymentId),
            ],
            'version' => 'required|integer|min:0',
        ];
    }
}
