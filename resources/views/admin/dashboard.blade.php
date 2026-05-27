@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Panel de Administración</h1>

<!-- Tarjetas de estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Ingresos (General)</p>
        <p class="text-2xl font-bold text-green-600">${{ number_format($totalIngresos, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Ventas Hoy</p>
        <p class="text-2xl font-bold text-blue-600">{{ $ventasHoy }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Ventas del Mes</p>
        <p class="text-2xl font-bold text-blue-600">{{ $ventasMes }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Clientes Activos</p>
        <p class="text-2xl font-bold text-purple-600">{{ $clientesActivos }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Con Membresía Activa</p>
        <p class="text-2xl font-bold text-teal-600">{{ $clientesConMembresia }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Ingresos por Productos</p>
        <p class="text-2xl font-bold text-orange-600">${{ number_format($totalVentasProductos, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Ingresos por Membresías</p>
        <p class="text-2xl font-bold text-orange-600">${{ number_format($totalVentasMembresias, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Alertas</p>
        <p class="text-2xl font-bold @if($reportesPendientes > 0 || $productosSinStock > 0) text-red-600 @else text-green-600 @endif">
            {{ $reportesPendientes + $productosSinStock }}
        </p>
        <p class="text-xs text-gray-400">{{ $reportesPendientes }} reportes · {{ $productosSinStock }} sin stock</p>
    </div>
</div>

<!-- Últimas ventas -->
<div class="bg-white rounded-lg shadow">
    <div class="p-5 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Últimas 10 Ventas</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Fecha</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Tipo</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Cliente</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Vendedor</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Método</th>
                    <th class="px-4 py-3 text-right text-gray-500 font-medium">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($ultimasVentas as $venta)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs rounded-full
                            @if($venta->tipo === 'producto') bg-blue-100 text-blue-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ ucfirst($venta->tipo) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $venta->cliente?->nombreCompleto() ?? 'Sin cliente' }}</td>
                    <td class="px-4 py-3">{{ $venta->usuario->name }}</td>
                    <td class="px-4 py-3">{{ ucfirst($venta->metodo_pago) }}</td>
                    <td class="px-4 py-3 text-right font-medium">${{ number_format($venta->total, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">No hay ventas registradas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection