<?php

namespace App\Http\Requests\Club;

use Illuminate\Foundation\Http\FormRequest;

class ClubCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_id' => ['nullable', 'exists:files,id'],
            'parent_id' => ['nullable', 'exists:clubs,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'type_id' => ['nullable', 'exists:club_types,id'],
            'short_name_ru' => ['required', 'string', 'max:255'],
            'short_name_kk' => ['required', 'string', 'max:255'],
            'short_name_en' => ['required', 'string', 'max:255'],
            'full_name_ru' => ['required', 'string', 'max:255'],
            'full_name_kk' => ['required', 'string', 'max:255'],
            'full_name_en' => ['required', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_kk' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'bin' => ['nullable', 'regex:/^[0-9]{12}$/', 'max:12'],
            'foundation_date' => ['nullable', 'date'],
            'address_ru' => ['nullable', 'string'],
            'address_kk' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'short_name_ru.required' => __('validation.required', ['attribute' => __('crud.short_name_ru')]),
            'short_name_kk.required' => __('validation.required', ['attribute' => __('crud.short_name_kk')]),
            'short_name_en.required' => __('validation.required', ['attribute' => __('crud.short_name_en')]),
            'full_name_ru.required' => __('validation.required', ['attribute' => __('crud.full_name_ru')]),
            'full_name_kk.required' => __('validation.required', ['attribute' => __('crud.full_name_kk')]),
            'full_name_en.required' => __('validation.required', ['attribute' => __('crud.full_name_en')]),
            'bin.regex' => __('crud.bin_format'),
            'bin.size' => __('crud.bin_size'),
        ];
    }
}
