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

    public function rules(): array
    {
        $id = $this->route('sport_type')?->id;

        return [
            'name'        => ['required', 'string', 'max:191', Rule::unique('sport_types', 'name')->ignore($id)],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên loại môn.',
            'name.unique'   => 'Tên loại môn đã tồn tại.',
            'image.image'   => 'File phải là ảnh.',
            'image.max'     => 'Ảnh tối đa 2MB.',
        ];
    }
}