<?php

namespace App\Http\Requests\ClubType;

use Illuminate\Foundation\Http\FormRequest;

class ClubTypeUpdateRequest extends FormRequest
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
        $clubTypeId = $this->editingId ?? $this->route('club_type') ?? $this->input('club_type_id');

        return [
            'file_id' => ['nullable', 'exists:files,id'],
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:280', 'unique:club_types,value,' . $clubTypeId],
            'level' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
