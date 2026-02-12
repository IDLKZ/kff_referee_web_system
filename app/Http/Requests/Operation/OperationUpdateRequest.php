<?php

namespace App\Http\Requests\Operation;

use Illuminate\Foundation\Http\FormRequest;

class OperationUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('operations.update');
    }

    public function rules(): array
    {
        $operationId = $this->route('id');

        return [
            'category_id' => ['required', 'exists:category_operations,id'],
            'title_ru' => ['required', 'string', 'max:255'],
            'title_kk' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string', 'max:2000'],
            'description_kk' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'value' => ['required', 'string', 'max:280', 'unique:operations,value,' . $operationId],
            'is_first' => ['boolean'],
            'is_last' => ['boolean'],
            'can_reject' => ['boolean'],
            'is_active' => ['boolean'],
            'result' => ['nullable', 'integer', 'min:0'],
            'previous_id' => ['nullable', 'exists:operations,id,' . $operationId],
            'next_id' => ['nullable', 'exists:operations,id,' . $operationId],
            'on_reject_id' => ['nullable', 'exists:operations,id,' . $operationId],
        ];
    }
}
