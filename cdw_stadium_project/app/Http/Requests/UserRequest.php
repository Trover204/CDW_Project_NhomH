<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        $rules = [
            'name'     => 'required|string|max:191',
            'email'    => [
                'required',
                'email',
                'max:191',
                Rule::unique('users')->ignore($userId),
            ],
            'phone'    => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore($userId),
            ],
            'avatar'   => 'nullable|string|max:191',
            'birthday' => 'nullable|date|before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
            'role'     => 'required|in:admin,staff,customer',
            'status'   => 'required|boolean',
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:6|max:191';
        } else {
            $rules['password'] = 'nullable|string|min:6|max:191';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên người dùng.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'birthday.before_or_equal' => 'Người dùng phải đủ 16 tuổi.',
        ];
    }
}
