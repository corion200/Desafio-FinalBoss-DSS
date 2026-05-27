@extends('layouts.app')

@section('title', 'Detalle de Cliente')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">{{ $cliente->nombreCompleto() }}</h1>
    <a href="{{ route('coach.clientes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">Volver a Mis Clientes</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold border-b pb-2 mb-3">Datos del Cliente</h2>
        <div class="text-sm space-y-2 text-gray-600">
            <p><strong>Cédula:</strong> {{ $cliente->cedula }}</p>
            <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
            <p><strong>Membresía:</strong> 
                @if($cliente->membresiaClientes->count() > 0)
                    <span class="text-green-600">Activa (Vence: {{ $cliente->membresiaClientes->first()->fecha_fin->format('d/m/Y') }})</span>
                @else
                    <span class="text-red-500">Sin membresía vigente</span>
                @endif
            </p>
        </div>
    </div>

    <div class="lg:col-span-2">
        <!-- Botón Crear/Editar Rutina -->
        <div class="flex justify-end mb-4">
            @if($rutinaActiva)
                <a href="{{ route('coach.rutinas.edit', $rutinaActiva) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition">✏️ Editar Rutina Actual</a>
            @else
                <a href="{{ route('coach.rutinas.create', $cliente) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm transition">➕ Crear Nueva Rutina</a>
            @endif
        </div>

        <!-- Mostrar Rutina Activa por Días -->
        @if($rutinaActiva)
            <div class="bg-white rounded-lg shadow p-5 mb-6">
                <h2 class="text-xl font-bold text-indigo-700 mb-1">{{ $rutinaActiva->nombre }}</h2>
                <p class="text-sm text-gray-500 mb-1"><strong>Objetivo:</strong> {{ $rutinaActiva->objetivo ?? 'General' }}</p>
                @if($rutinaActiva->observaciones)
                    <p class="text-sm text-gray-500 italic mb-4">"{{ $rutinaActiva->observaciones }}"</p>
                @endif

                <div class="space-y-6">
                    @php
                        $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                        $ejerciciosPorDia = $rutinaActiva->ejercicios->groupBy('dia_semana');
                    @endphp

                    @foreach($dias as $dia)
                        @if($ejerciciosPorDia->has($dia))
                            <div>
                                <h3 class="font-bold text-gray-800 border-b-2 border-indigo-200 pb-1 mb-3 uppercase text-sm">{{ ucfirst($dia) }}</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="text-gray-500 text-xs">
                                                <th class="pb-2 text-left">Ejercicio</th>
                                                <th class="pb-2 text-left">Grupo Muscular</th>
                                                <th class="pb-2 text-center">Series</th>
                                                <th class="pb-2 text-center">Reps</th>
                                                <th class="pb-2 text-center">Descanso</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ejerciciosPorDia->get($dia) as $ej)
                                            <tr class="border-b border-gray-100">
                                                <td class="py-2 font-medium">{{ $ej->nombre }}</td>
                                                <td class="py-2 text-gray-600">{{ $ej->grupo_muscular }}</td>
                                                <td class="py-2 text-center">{{ $ej->series }}</td>
                                                <td class="py-2 text-center">{{ $ej->repeticiones }}</td>
                                                <td class="py-2 text-center text-gray-500">{{ $ej->descanso_segundos ?? '-' }}s</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-gray-50 border-dashed border-2 border-gray-300 rounded-lg p-12 text-center">
                <p class="text-gray-500 text-lg">Este cliente aún no tiene una rutina asignada.</p>
                <a href="{{ route('coach.rutinas.create', $cliente) }}" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg">Crear Rutina Ahora</a>
            </div>
        @endif
    </div>
</div>
@endsection