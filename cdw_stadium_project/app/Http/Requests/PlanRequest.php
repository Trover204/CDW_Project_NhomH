<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('version')) {
            $plan = $this->route('plan');
            $this->merge([
                'version' => $plan instanceof \App\Models\Plan ? (int) $plan->version : 0,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|between:0,100',
            'status' => 'boolean',
            'version' => 'required|integer|min:0',
        ];
    }
}
