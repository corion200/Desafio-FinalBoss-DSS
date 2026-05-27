<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EjercicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ejercicios'                    => ['required', 'array', 'min:1'],
            'ejercicios.*.nombre'           => ['required', 'string', 'max:150'],
            'ejercicios.*.grupo_muscular'   => ['required', 'string', 'max:100'],
            'ejercicios.*.series'           => ['required', 'integer', 'min:1', 'max:20'],
            'ejercicios.*.repeticiones'     => ['required', 'integer', 'min:1', 'max:100'],
            'ejercicios.*.descanso_segundos'=> ['nullable', 'integer', 'min:0'],
            'ejercicios.*.dia_semana'       => ['required', 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo'],
            'ejercicios.*.notas'            => ['nullable', 'string', 'max:300'],
        ];
    }

    public function messages(): array
    {
        return [
            'ejercicios.required'                        => 'Debe agregar al menos un ejercicio.',
            'ejercicios.*.nombre.required'               => 'El nombre del ejercicio es obligatorio.',
            'ejercicios.*.grupo_muscular.required'       => 'El grupo muscular es obligatorio.',
            'ejercicios.*.series.required'               => 'Las series son obligatorias.',
            'ejercicios.*.series.min'                    => 'Las series deben ser al menos 1.',
            'ejercicios.*.repeticiones.required'         => 'Las repeticiones son obligatorias.',
            'ejercicios.*.repeticiones.min'              => 'Las repeticiones deben ser al menos 1.',
            'ejercicios.*.dia_semana.required'           => 'El día de la semana es obligatorio.',
            'ejercicios.*.dia_semana.in'                 => 'El día de la semana no es válido.',
        ];
    }
}