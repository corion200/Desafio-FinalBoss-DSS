@extends('layouts.app')

@section('title', 'Mis Reportes de Equipo')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Mis Reportes de Equipo</h1>
    <a href="{{ route('coach.reportes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
        + Nuevo Reporte
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Equipo</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Ubicación</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Descripción</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-600">Prioridad</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-600">Estado</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($reportes as $reporte)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $reporte->equipo_nombre }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $reporte->ubicacion ?? 'N/D' }}</td>
                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $reporte->descripcion }}">
                        {{ $reporte->descripcion }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($reporte->prioridad === 'alta')
                            <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">🔴 Alta</span>
                        @elseif($reporte->prioridad === 'media')
                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">🟡 Media</span>
                        @else
                            <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">🟢 Baja</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($reporte->estado === 'resuelto')
                            <span class="px-2.5 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium border border-green-200">✅ Resuelto</span>
                        @elseif($reporte->estado === 'en_reparacion')
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium border border-blue-200">🔧 En reparación</span>
                        @else
                            <span class="px-2.5 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-medium border border-orange-200">⏳ Pendiente</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $reporte->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <p class="text-lg">No has enviado ningún reporte aún.</p>
                        <a href="{{ route('coach.reportes.create') }}" class="mt-3 inline-block text-indigo-600 hover:underline text-sm">Crear el primer reporte →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reportes->hasPages())
        <div class="p-4 border-t bg-gray-50">
            {{ $reportes->links() }}
        </div>
    @endif
</div>
@endsection
