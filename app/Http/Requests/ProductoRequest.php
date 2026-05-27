<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productoId = $this->route('producto') ? $this->route('producto')->id : null;

        return [
            'nombre'      => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio'      => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'stock'       => ['required', 'integer', 'min:0'],
            'categoria'   => ['required', 'in:suplemento,equipamiento,accesorio,ropa'],
            'activo'      => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'    => 'El nombre del producto es obligatorio.',
            'precio.required'    => 'El precio es obligatorio.',
            'precio.numeric'     => 'El precio debe ser un número válido.',
            'precio.min'         => 'El precio no puede ser negativo.',
            'stock.required'     => 'El stock es obligatorio.',
            'stock.integer'      => 'El stock debe ser un número entero.',
            'stock.min'          => 'El stock no puede ser negativo.',
            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in'       => 'La categoría seleccionada no es válida.',
        ];
    }
}