@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Editar Producto: {{ $producto->nombre }}</h1>
    <a href="{{ route('admin.productos.show', $producto) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
        ← Ver Detalle
    </a>
</div>

<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.productos.update', $producto) }}">
        @csrf
        @method('PUT') <!-- IMPORTANTE: Para indicar que es una actualización -->

        <!-- Mostrar Errores de Validación -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                <p class="font-bold mb-2">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-5">
            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto <span class="text-red-500">*</span></label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" 
                    required>
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <!-- Precio, Stock y Categoría -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio ($) <span class="text-red-500">*</span></label>
                    <input type="number" id="precio" name="precio" step="0.01" value="{{ old('precio', $producto->precio) }}" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" 
                        required>
                </div>
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Actual <span class="text-red-500">*</span></label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $producto->stock) }}" min="0" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" 
                        required>
                </div>
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                    <select id="categoria" name="categoria" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition" required>
                        <option value="">Seleccionar...</option>
                        <option value="suplemento" @if(old('categoria', $producto->categoria) === 'suplemento') selected @endif>Suplemento</option>
                        <option value="equipamiento" @if(old('categoria', $producto->categoria) === 'equipamiento') selected @endif>Equipamiento</option>
                        <option value="accesorio" @if(old('categoria', $producto->categoria) === 'accesorio') selected @endif>Accesorio</option>
                        <option value="ropa" @if(old('categoria', $producto->categoria) === 'ropa') selected @endif>Ropa</option>
                    </select>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end pt-4 border-t space-x-4">
                <a href="{{ route('admin.productos.show', $producto) }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium transition shadow-sm">
                    Actualizar Producto
                </button>
            </div>
        </div>
    </form>
</div>
@endsection