<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:191'],
            'keyword' => ['nullable', 'string', 'max:191'],
            'type' => [
                'nullable',
                'string',
                'in:all,courts,facilities,products,news,sport_types,bookings,users,sales,payments,plans,subscriptions,reviews,ads,price_rules',
            ],
            'search_after' => ['nullable', 'array'],
        ];
    }

    public function keyword(): string
    {
        return trim((string) ($this->validated('q') ?? $this->validated('keyword') ?? ''));
    }

    public function searchType(): string
    {
        return (string) ($this->validated('type') ?? 'all');
    }

    /**
     * @return array<int, scalar>
     */
    public function searchAfterCursor(): array
    {
        $cursor = $this->validated('search_after');

        return is_array($cursor) ? array_values($cursor) : [];
    }
}
