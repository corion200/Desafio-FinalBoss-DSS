<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id'   => ['nullable', 'exists:clientes,id'],
            'metodo_pago'  => ['required', 'in:efectivo,tarjeta,transferencia'],
            'notas'        => ['nullable', 'string', 'max:500'],
            'productos'    => ['required', 'array', 'min:1'],
            'productos.*.id'       => ['required', 'exists:productos,id'],
            'productos.*.cantidad' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'metodo_pago.required'      => 'Debe seleccionar un método de pago.',
            'metodo_pago.in'             => 'El método de pago no es válido.',
            'productos.required'         => 'Debe agregar al menos un producto.',
            'productos.array'            => 'El formato de productos no es válido.',
            'productos.min'              => 'Debe agregar al menos un producto.',
            'productos.*.id.required'    => 'Cada producto debe tener un ID válido.',
            'productos.*.id.exists'      => 'Uno de los productos no existe.',
            'productos.*.cantidad.required' => 'La cantidad es obligatoria para cada producto.',
            'productos.*.cantidad.min'   => 'La cantidad debe ser al menos 1.',
        ];
    }
}