<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
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
        if (! $this->filled('version')) {
            $ad = $this->route('ad');
            $this->merge([
                'version' => $ad instanceof \App\Models\Ad ? (int) $ad->version : 0,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'image' => 'nullable|image|max:2048', // Allow images up to 2MB
            'link_url' => 'nullable|url|max:191',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'version' => 'required|integer|min:0',
        ];
    }
}
