<?php

namespace App\Http\Requests\RoomFacility;

use Illuminate\Foundation\Http\FormRequest;

class RoomFacilityCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'exists:hotel_rooms,id'],
            'facility_id' => ['required', 'exists:facilities,id'],
        ];
    }
}
