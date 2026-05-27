@extends('layouts.app')

@section('title', 'Reportar Equipo Dañado')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Reportar Equipo Dañado</h1>
    <a href="{{ route('coach.reportes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
        ← Mis Reportes
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('coach.reportes.store') }}">
        @csrf

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                <p class="font-bold mb-1">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-5">
            {{-- Equipo y Ubicación --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="equipo_nombre" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del Equipo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="equipo_nombre" name="equipo_nombre" value="{{ old('equipo_nombre') }}"
                        placeholder="Ej: Cinta de correr #3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                        required>
                </div>
                <div>
                    <label for="ubicacion" class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                    <input type="text" id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}"
                        placeholder="Ej: Sala principal, piso 2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
            </div>

            {{-- Prioridad --}}
            <div>
                <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-1">
                    Prioridad <span class="text-red-500">*</span>
                </label>
                <select id="prioridad" name="prioridad"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                    required>
                    <option value="">Seleccionar prioridad...</option>
                    <option value="baja"  @if(old('prioridad') === 'baja')  selected @endif>🟢 Baja — No interfiere con el uso normal</option>
                    <option value="media" @if(old('prioridad') === 'media') selected @endif>🟡 Media — Funciona parcialmente</option>
                    <option value="alta"  @if(old('prioridad') === 'alta')  selected @endif>🔴 Alta — Fuera de servicio / riesgo</option>
                </select>
            </div>

            {{-- Descripción --}}
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                    Descripción del Problema <span class="text-red-500">*</span>
                </label>
                <textarea id="descripcion" name="descripcion" rows="4"
                    placeholder="Describe el daño o falla que presenta el equipo con el mayor detalle posible..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                    required>{{ old('descripcion') }}</textarea>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end pt-4 border-t space-x-4">
                <a href="{{ route('coach.reportes.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm">
                    Enviar Reporte
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
