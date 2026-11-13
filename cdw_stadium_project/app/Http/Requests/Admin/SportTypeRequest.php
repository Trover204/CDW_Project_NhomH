<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SportTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('version')) {
            $sportType = $this->route('sport_type');
            $this->merge([
                'version' => $sportType instanceof \App\Models\SportType ? (int) $sportType->version : 0,
            ]);
        }
    }

    public function rules(): array
    {
        $sportType = $this->route('sport_type');
        $id = $sportType instanceof \App\Models\SportType ? $sportType->id : $sportType;

        return [
            'name' => ['required', 'string', 'max:191', Rule::unique('sport_types', 'name')->ignore($id)],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'boolean'],
            'version' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên loại môn.',
            'name.unique' => 'Tên loại môn đã tồn tại.',
            'image.image' => 'File phải là ảnh.',
            'image.max' => 'Ảnh tối đa 2MB.',
        ];
    }
}
