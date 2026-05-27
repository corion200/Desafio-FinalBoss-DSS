@extends('layouts.app')

@section('title', 'Registrar Cliente')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Registrar Nuevo Cliente</h1>

<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('clientes.store') }}">
        @csrf

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido *</label>
                <input type="text" name="apellido" value="{{ old('apellido') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cédula *</label>
                <input type="text" name="cedula" value="{{ old('cedula') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Asignar Coach</label>
                <select name="coach_id" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Seleccionar Coach (Opcional) --</option>
                    @foreach($coaches as $coach)
                        <option value="{{ $coach->id }}" @if(old('coach_id') == $coach->id) selected @endif>{{ $coach->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
            <textarea name="direccion" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('direccion') }}</textarea>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">Guardar Cliente</button>
            <a href="{{ route('clientes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition">Cancelar</a>
        </div>
    </form>
</div>
@endsection