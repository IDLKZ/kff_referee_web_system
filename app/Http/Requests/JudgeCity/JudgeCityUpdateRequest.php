<?php

namespace App\Http\Requests\JudgeCity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JudgeCityUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('judge_city') ?? $this->input('judge_city_id');

        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'city_id' => [
                'required', 'integer', 'exists:cities,id',
                Rule::unique('judge_cities')
                    ->where('user_id', $this->input('user_id'))
                    ->whereNull('deleted_at')
                    ->ignore($id),
            ],
        ];
    }
}
