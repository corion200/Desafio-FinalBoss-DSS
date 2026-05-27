<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente') ? $this->route('cliente')->id : null;

        return [
            'nombre'           => ['required', 'string', 'max:100'],
            'apellido'         => ['required', 'string', 'max:100'],
            'cedula'           => ['required', 'string', 'max:20', 'unique:clientes,cedula,' . $clienteId],
            'email'            => ['nullable', 'string', 'email', 'max:150'],
            'telefono'         => ['required', 'string', 'max:20'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'direccion'        => ['nullable', 'string', 'max:255'],
            'coach_id'         => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'           => 'El nombre es obligatorio.',
            'apellido.required'         => 'El apellido es obligatorio.',
            'cedula.required'           => 'La cédula es obligatoria.',
            'cedula.unique'             => 'Ya existe un cliente con esa cédula.',
            'email.email'               => 'Debe ingresar un correo electrónico válido.',
            'telefono.required'         => 'El teléfono es obligatorio.',
            'fecha_nacimiento.before'   => 'La fecha de nacimiento debe ser anterior a hoy.',
            'coach_id.exists'           => 'El coach seleccionado no existe.',
        ];
    }
}