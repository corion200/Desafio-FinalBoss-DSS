<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReporteEquipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'equipo_nombre' => ['required', 'string', 'max:150'],
            'ubicacion'     => ['nullable', 'string', 'max:150'],
            'descripcion'   => ['required', 'string', 'max:500'],
            'prioridad'     => ['required', 'in:baja,media,alta'],
        ];
    }

    public function messages(): array
    {
        return [
            'equipo_nombre.required' => 'El nombre del equipo es obligatorio.',
            'descripcion.required'   => 'La descripción del problema es obligatoria.',
            'prioridad.required'     => 'La prioridad es obligatoria.',
            'prioridad.in'           => 'La prioridad seleccionada no es válida.',
        ];
    }
}