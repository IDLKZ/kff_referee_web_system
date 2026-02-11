<?php

namespace App\Http\Requests\ClubType;

use Illuminate\Foundation\Http\FormRequest;

class ClubTypeCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_id' => ['nullable', 'exists:files,id'],
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:280', 'unique:club_types,value'],
            'level' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
