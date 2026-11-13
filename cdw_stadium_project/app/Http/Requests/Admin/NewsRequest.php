<?php

namespace App\Http\Requests\Admin;

use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $news = $this->route('news');
        $newsId = $news instanceof News ? $news->id : $news;

        return [
            'title' => 'required|string|max:191',
            'slug' => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('news', 'slug')->ignore($newsId),
            ],
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:191',
            'status' => 'required|in:draft,published',
        ];
    }
}

