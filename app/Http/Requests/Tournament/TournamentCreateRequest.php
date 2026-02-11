<?php

namespace App\Http\Requests\Tournament;

use Illuminate\Foundation\Http\FormRequest;

class TournamentCreateRequest extends FormRequest
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
            'short_title_ru' => ['nullable', 'string', 'max:255'],
            'short_title_kk' => ['nullable', 'string', 'max:255'],
            'short_title_en' => ['nullable', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_kk' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'value' => ['required', 'string', 'max:255', 'unique:tournaments,value'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'level' => ['required', 'integer', 'min:1'],
            'sex' => ['required', 'in:0,1,2'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
