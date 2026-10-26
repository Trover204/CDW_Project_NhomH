<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RevenueReportRequest extends FormRequest
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
            'period' => ['nullable', 'string', 'in:month,year,custom'],
            'month' => ['nullable', 'string', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'year' => ['nullable', 'integer', 'min:2020', 'max:2035'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'sport_type_id' => ['nullable', 'integer', 'exists:sport_types,id'],
            'facility_id' => ['nullable', 'integer', 'exists:facilities,id'],
            'payable_type' => ['nullable', 'string', 'in:booking,sale,subscription'],
        ];
    }

    /**
     * Custom message for validation errors.
     */
    public function messages(): array
    {
        return [
            'month.regex' => 'Tháng không hợp lệ (định dạng YYYY-MM).',
            'to_date.after_or_equal' => 'Khoảng thời gian không hợp lệ.',
            'sport_type_id.exists' => 'Môn thể thao không tồn tại.',
            'facility_id.exists' => 'Cơ sở không tồn tại.',
        ];
    }
}
