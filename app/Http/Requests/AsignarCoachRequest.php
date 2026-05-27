<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignarCoachRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coach_id' => ['required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'coach_id.required' => 'Debe seleccionar un coach.',
            'coach_id.exists'   => 'El coach seleccionado no existe.',
        ];
    }
}