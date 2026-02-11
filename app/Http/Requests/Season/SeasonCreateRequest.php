<?php

namespace App\Http\Requests\Season;

use Illuminate\Foundation\Http\FormRequest;

class SeasonCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:280', 'unique:seasons,value'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_at.after_or_equal' => __('crud.end_date_after_start'),
        ];
    }
}
