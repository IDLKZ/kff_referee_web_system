<?php

namespace App\Http\Requests\TransportType;

use Illuminate\Foundation\Http\FormRequest;

class TransportTypeCreateRequest extends FormRequest
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
            'value' => ['required', 'string', 'max:280', 'unique:transport_types,value'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_ru.required' => __('validation.required', ['attribute' => __('crud.title_ru')]),
            'title_ru.max' => __('validation.max.string', ['attribute' => __('crud.title_ru'), 'max' => 255]),
            'title_kk.max' => __('validation.max.string', ['attribute' => __('crud.title_kk'), 'max' => 255]),
            'title_en.max' => __('validation.max.string', ['attribute' => __('crud.title_en'), 'max' => 255]),
            'value.required' => __('validation.required', ['attribute' => __('crud.value')]),
            'value.max' => __('validation.max.string', ['attribute' => __('crud.value'), 'max' => 280]),
            'value.unique' => __('validation.unique', ['attribute' => __('crud.value')]),
        ];
    }
}
