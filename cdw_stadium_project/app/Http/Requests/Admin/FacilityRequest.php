<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('facility')?->id;

        return [
            'name'       => ['required', 'string', 'max:191'],
            'address'    => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities', 'address')
                    ->where(fn ($query) => $query->where('name', $this->name))
                    ->ignore($id),
            ],
            'phone'      => ['nullable', 'string', 'max:20'],
            'open_time'  => ['required', 'date_format:H:i'],
            'close_time' => ['required', 'date_format:H:i', 'after:open_time'],
            'status'     => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Chuẩn hóa định dạng thời gian về H:i nếu có giây
        $merge = [];
        if ($this->has('open_time') && is_string($this->open_time)) {
            $merge['open_time'] = substr($this->open_time, 0, 5);
        }
        if ($this->has('close_time') && is_string($this->close_time)) {
            $merge['close_time'] = substr($this->close_time, 0, 5);
        }
        if (!empty($merge)) {
            $this->merge($merge);
        }
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Vui lòng nhập tên cơ sở.',
            'name.max'            => 'Tên cơ sở không được vượt quá 191 ký tự.',
            'address.required'    => 'Vui lòng nhập địa chỉ cơ sở.',
            'address.unique'      => 'Cơ sở với tên và địa chỉ này đã tồn tại.',
            'open_time.required'  => 'Vui lòng chọn giờ mở cửa.',
            'close_time.required' => 'Vui lòng chọn giờ đóng cửa.',
            'close_time.after'    => 'Giờ đóng cửa phải sau giờ mở cửa.',
        ];
    }
}
