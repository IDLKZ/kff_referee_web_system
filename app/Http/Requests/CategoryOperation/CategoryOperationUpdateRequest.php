<?php

namespace App\Http\Requests\CategoryOperation;

use Illuminate\Foundation\Http\FormRequest;

class CategoryOperationUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category_operation') ?? $this->input('id');

        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:280', 'unique:category_operations,value,' . $id],
            'is_first' => ['boolean'],
            'is_last' => ['boolean'],
            'previous_id' => ['nullable', 'exists:category_operations,id'],
            'next_id' => ['nullable', 'exists:category_operations,id'],
        ];
    }
}
