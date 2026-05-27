@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Cliente: {{ $cliente->nombreCompleto() }}</h1>

<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('clientes.update', $cliente) }}">
        @csrf @method('PUT')

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido *</label>
                <input type="text" name="apellido" value="{{ old('apellido', $cliente->apellido) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cédula *</label>
                <input type="text" name="cedula" value="{{ old('cedula', $cliente->cedula) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" placeholder="Ej: 1234-5678" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
            <input type="email" name="email" value="{{ old('email', $cliente->email) }}" class="w-full px-3 py-2 border rounded-lg">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento?->format('Y-m-d')) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Asignar Coach</label>
                <select name="coach_id" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">-- Sin Coach --</option>
                    @foreach($coaches as $coach)
                        <option value="{{ $coach->id }}" @if(old('coach_id', $cliente->coach_id) == $coach->id) selected @endif>{{ $coach->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">Actualizar Datos</button>
            <a href="{{ route('clientes.show', $cliente) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition">Cancelar</a>
        </div>
    </form>
</div>
@endsection