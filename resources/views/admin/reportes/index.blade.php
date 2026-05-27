@extends('layouts.app')
@section('title', 'Reportes de Equipo')
@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Reportes de Equipo Dañado</h1>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 text-center border-t-4 border-orange-500">
        <p class="text-xs text-gray-500">Pendientes</p>
        <p class="text-2xl font-bold text-orange-600">{{ $estadisticas['pendientes'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center border-t-4 border-blue-500">
        <p class="text-xs text-gray-500">En Reparación</p>
        <p class="text-2xl font-bold text-blue-600">{{ $estadisticas['en_reparacion'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center border-t-4 border-green-500">
        <p class="text-xs text-gray-500">Resueltos</p>
        <p class="text-2xl font-bold text-green-600">{{ $estadisticas['resueltos'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center border-t-4 border-red-500">
        <p class="text-xs text-gray-500">Alta Prioridad</p>
        <p class="text-2xl font-bold text-red-600">{{ $estadisticas['alta_prioridad'] }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.reportes.index') }}" class="flex gap-4">
        <select name="estado" class="px-3 py-2 border rounded-lg text-sm">
            <option value="">Todos los estados</option>
            <option value="pendiente" @if(request('estado') === 'pendiente') selected @endif>Pendientes</option>
            <option value="en_reparacion" @if(request('estado') === 'en_reparacion') selected @endif>En reparación</option>
            <option value="resuelto" @if(request('estado') === 'resuelto') selected @endif>Resueltos</option>
        </select>
        <select name="prioridad" class="px-3 py-2 border rounded-lg text-sm">
            <option value="">Todas las prioridades</option>
            <option value="alta" @if(request('prioridad') === 'alta') selected @endif>Alta</option>
            <option value="media" @if(request('prioridad') === 'media') selected @endif>Media</option>
            <option value="baja" @if(request('prioridad') === 'baja') selected @endif>Baja</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Filtrar</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500">Fecha</th>
                <th class="px-4 py-3 text-left text-gray-500">Equipo</th>
                <th class="px-4 py-3 text-left text-gray-500">Reportado por</th>
                <th class="px-4 py-3 text-left text-gray-500">Prioridad</th>
                <th class="px-4 py-3 text-left text-gray-500">Estado</th>
                <th class="px-4 py-3 text-center text-gray-500">Acción</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($reportes as $reporte)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ $reporte->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3 font-medium">{{ $reporte->equipo_nombre }}</td>
                <td class="px-4 py-3">{{ $reporte->coach->name }}</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs rounded-full @if($reporte->prioridad === 'alta') bg-red-100 text-red-700 @elseif($reporte->prioridad === 'media') bg-yellow-100 text-yellow-700 @else bg-green-100 text-green-700 @endif">{{ ucfirst($reporte->prioridad) }}</span></td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs rounded-full @if($reporte->estado === 'pendiente') bg-orange-100 text-orange-700 @elseif($reporte->estado === 'en_reparacion') bg-blue-100 text-blue-700 @else bg-green-100 text-green-700 @endif">{{ str_replace('_', ' ', ucfirst($reporte->estado)) }}</span></td>
                <td class="px-4 py-3 text-center"><a href="{{ route('admin.reportes.show', $reporte) }}" class="text-indigo-600 hover:underline text-sm">Gestionar</a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">Sin reportes.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection