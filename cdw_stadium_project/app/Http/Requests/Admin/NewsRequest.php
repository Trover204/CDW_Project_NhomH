<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:191',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:191',
            'status' => 'required|in:draft,published',
        ];

        if ($this->isMethod('post')) {
            $rules['slug'] = 'nullable|string|max:191|unique:news,slug';
        } else {
            $rules['slug'] = 'nullable|string|max:191|unique:news,slug,'.$this->route('news')->id;
        }

        return $rules;
    }
}
