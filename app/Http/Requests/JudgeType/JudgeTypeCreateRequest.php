<?php

namespace App\Http\Requests\JudgeType;

use Illuminate\Foundation\Http\FormRequest;

class JudgeTypeCreateRequest extends FormRequest
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
            'value' => ['required', 'string', 'max:255', 'unique:judge_types,value'],
            'is_active' => ['boolean'],
        ];
    }
}
