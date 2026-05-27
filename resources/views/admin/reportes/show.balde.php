@extends('layouts.app')
@section('title', 'Gestionar Reporte')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Reporte: {{ $reporteEquipo->equipo_nombre }}</h1>
    <a href="{{ route('admin.reportes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">Volver</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold border-b pb-2 mb-3">Detalles del Reporte</h2>
        <div class="text-sm space-y-2 text-gray-600">
            <p><strong>Fecha:</strong> {{ $reporteEquipo->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Coach:</strong> {{ $reporteEquipo->coach->name }}</p>
            <p><strong>Ubicación:</strong> {{ $reporteEquipo->ubicacion ?? 'No especificada' }}</p>
            <p><strong>Prioridad:</strong> <span class="px-2 py-0.5 text-xs rounded-full @if($reporteEquipo->prioridad === 'alta') bg-red-100 text-red-700 @else bg-gray-100 @endif">{{ ucfirst($reporteEquipo->prioridad) }}</span></p>
            <div class="mt-4 p-3 bg-gray-50 rounded">
                <p class="font-medium text-gray-800 mb-1">Descripción del daño:</p>
                <p class="italic">{{ $reporteEquipo->descripcion }}</p>
            </div>
            @if($reporteEquipo->resolucion)
            <div class="mt-4 p-3 bg-green-50 rounded">
                <p class="font-medium text-green-800 mb-1">Resolución:</p>
                <p>{{ $reporteEquipo->resolucion }}</p>
                <p class="text-xs mt-1 text-green-600">Resuelto el: {{ $reporteEquipo->fecha_resolucion->format('d/m/Y') }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold mb-4">Cambiar Estado</h2>
        <form method="POST" action="{{ route('admin.reportes.update-estado', $reporteEquipo) }}">
            @csrf @method('PUT')
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nuevo Estado</label>
                <select name="estado" id="estado-select" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="pendiente" {{ $reporteEquipo->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_reparacion" {{ $reporteEquipo->estado === 'en_reparacion' ? 'selected' : '' }}>En Reparación</option>
                    <option value="resuelto" {{ $reporteEquipo->estado === 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                </select>
            </div>
            
            <div id="resolucion-box" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la Resolución *</label>
                <textarea name="resolucion" rows="4" class="w-full px-3 py-2 border rounded-lg" placeholder="Explique cómo se solucionó el problema..."></textarea>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">Actualizar Estado</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('estado-select').addEventListener('change', function() {
        document.getElementById('resolucion-box').classList.toggle('hidden', this.value !== 'resuelto');
    });
    // Mostrar ocultar al cargar
    document.getElementById('resolucion-box').classList.toggle('hidden', document.getElementById('estado-select').value !== 'resuelto');
</script>
@endpush