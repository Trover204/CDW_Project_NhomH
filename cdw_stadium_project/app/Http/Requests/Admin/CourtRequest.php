<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('court')?->id;

        return [
            'facility_id'   => ['required', 'exists:facilities,id'],
            'sport_type_id' => ['required', 'exists:sport_types,id'],
            'name'          => [
                'required',
                'string',
                'max:191',
                Rule::unique('courts', 'name')
                    ->where(fn ($query) => $query->where('facility_id', $this->facility_id))
                    ->ignore($id),
            ],
                 'version'  => 'required|integer', // Thêm validate cho version
            'capacity'      => ['required', 'integer', 'min:1'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'status'        => ['required', Rule::in(['available', 'maintenance', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'facility_id.required'   => 'Vui lòng chọn cơ sở.',
            'facility_id.exists'     => 'Cơ sở được chọn không hợp lệ.',
            'sport_type_id.required' => 'Vui lòng chọn loại môn thể thao.',
            'sport_type_id.exists'   => 'Loại môn thể thao không hợp lệ.',
            'name.required'          => 'Vui lòng nhập tên sân.',
            'name.max'               => 'Tên sân không được vượt quá 191 ký tự.',
            'name.unique'            => 'Tên sân này đã tồn tại trong cơ sở đã chọn.',
            'capacity.required'      => 'Vui lòng nhập sức chứa.',
            'capacity.integer'       => 'Sức chứa phải là số nguyên.',
            'capacity.min'           => 'Sức chứa tối thiểu là 1 người.',
            'status.required'        => 'Vui lòng chọn trạng thái.',
            'status.in'              => 'Trạng thái không hợp lệ.',
        ];
    }
}
