<?php

namespace App\Http\Requests\MatchLogists;

use Illuminate\Foundation\Http\FormRequest;

class CreateMatchLogistsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('match_logists.create');
    }

    public function rules(): array
    {
        return [
            'match_id' => ['required', 'exists:matches,id'],
            'logist_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail) {
                $user = \App\Models\User::find($value);
                if (!$user || !$user->isActive()) {
                    $fail('logist_must_be_active', [
                        'user_id' => $value,
                    ]);
                }
                if ($user && $user->role->value !== \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN) {
                    $fail('logist_must_be_logistician', [
                        'user_id' => $value,
                    ]);
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'match_id.required' => __('validation.match_id_required'),
            'match_id.exists' => __('validation.match_id_exists'),
            'logist_id.required' => __('validation.logist_id_required'),
            'logist_id.exists' => __('validation.logist_id_exists'),
            'logist_id.logist_must_be_active' => __('validation.logist_must_be_active'),
            'logist_id.logist_must_be_logistician' => __('validation.logist_must_be_logistician'),
        ];
    }

    public function attributes(): array
    {
        return [
            'match_id' => __('crud.match'),
            'logist_id' => __('crud.logist'),
        ];
    }
}
