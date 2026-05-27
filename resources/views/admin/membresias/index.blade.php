@extends('layouts.app')
@section('title', 'Tipos de Membresía')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Gestionar Tipos de Membresía</h1>

<div class="bg-white rounded-lg shadow p-5 mb-6">
    <h2 class="font-semibold mb-4">Crear Nueva Membresía</h2>
    <form method="POST" action="{{ route('admin.membresias.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        @csrf
        <div>
            <label class="block text-xs text-gray-500 mb-1">Nombre</label>
            <input type="text" name="nombre" class="w-full px-3 py-2 border rounded-lg text-sm" required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Duración (Días)</label>
            <input type="number" name="duracion_dias" class="w-full px-3 py-2 border rounded-lg text-sm" min="1" required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Precio ($)</label>
            <input type="number" step="0.01" name="precio" class="w-full px-3 py-2 border rounded-lg text-sm" required>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Descripción</label>
            <input type="text" name="descripcion" class="w-full px-3 py-2 border rounded-lg text-sm">
        </div>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm h-[38px]">Crear</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500">Nombre</th>
                <th class="px-4 py-3 text-center text-gray-500">Duración</th>
                <th class="px-4 py-3 text-right text-gray-500">Precio</th>
                <th class="px-4 py-3 text-left text-gray-500">Descripción</th>
                <th class="px-4 py-3 text-center text-gray-500">Estado</th>
            </tr>
        </thead>
        <tbody class="divide-y" id="membresias-tbody">
            @foreach($membresias as $membresia)
            <tr class="hover:bg-gray-50" id="fila-{{ $membresia->id }}">
                <td class="px-4 py-3 font-medium">{{ $membresia->nombre }}</td>
                <td class="px-4 py-3 text-center">{{ $membresia->duracion_dias }} días</td>
                <td class="px-4 py-3 text-right font-bold">${{ number_format($membresia->precio, 2) }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $membresia->descripcion ?? '-' }}</td>
                <td class="px-4 py-3 text-center">
                    <form method="POST" action="{{ route('admin.membresias.update', $membresia) }}" class="inline-flex items-center gap-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="nombre" value="{{ $membresia->nombre }}">
                        <input type="hidden" name="duracion_dias" value="{{ $membresia->duracion_dias }}">
                        <input type="hidden" name="precio" value="{{ $membresia->precio }}">
                        <input type="hidden" name="descripcion" value="{{ $membresia->descripcion }}">
                        <input type="hidden" name="activa" value="{{ $membresia->activa ? '0' : '1' }}">
                        <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $membresia->activa ? 'bg-green-500' : 'bg-gray-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $membresia->activa ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection