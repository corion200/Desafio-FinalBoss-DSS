<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MembresiaClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'membresia_id' => ['required', 'exists:membresias,id'],
            'metodo_pago'  => ['required', 'in:efectivo,tarjeta,transferencia'],
            'notas'        => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'membresia_id.required' => 'Debe seleccionar un tipo de membresía.',
            'membresia_id.exists'   => 'La membresía seleccionada no existe.',
            'metodo_pago.required'  => 'Debe seleccionar un método de pago.',
            'metodo_pago.in'        => 'El método de pago seleccionado no es válido.',
        ];
    }
}