<?php

namespace App\Http\Requests\Stadium;

use Illuminate\Foundation\Http\FormRequest;

class StadiumUpdateRequest extends FormRequest
{
    protected ?int $editingId = null;

    public function authorize(): bool
    {
        return true;
    }

    public function setEditingId(int $id): void
    {
        $this->editingId = $id;
    }

    public function rules(): array
    {
        return [
            'file_id' => ['nullable', 'exists:files,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_kk' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'address_ru' => ['nullable', 'string'],
            'address_kk' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
            'built_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
