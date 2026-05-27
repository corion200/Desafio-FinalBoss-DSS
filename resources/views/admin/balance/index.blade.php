@extends('layouts.app')

@section('title', 'Balance Financiero')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Balance Financiero</h1>

<!-- Filtros de Fecha -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.balance.index') }}" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" class="px-3 py-2 border rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" class="px-3 py-2 border rounded-lg text-sm">
        </div>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Generar Reporte</button>
    </form>
</div>

<!-- Tarjetas Principales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Total Ingresos</p>
        <p class="text-3xl font-bold text-green-600">${{ number_format($totalIngresos, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
        <p class="text-sm text-gray-500">Por Productos</p>
        <p class="text-2xl font-bold text-blue-600">${{ number_format($ingresosProductos, 2) }}</p>
        <p class="text-xs text-gray-400">{{ $cantidadVentasProductos }} ventas</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
        <p class="text-sm text-gray-500">Por Membresías</p>
        <p class="text-2xl font-bold text-purple-600">${{ number_format($ingresosMembresias, 2) }}</p>
        <p class="text-xs text-gray-400">{{ $cantidadVentasMembresias }} ventas</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-500">
        <p class="text-sm text-gray-500">Ventas por Método de Pago</p>
        <div class="mt-2 space-y-1 text-sm">
            @foreach($ventasPorMetodoPago as $vp)
                <p class="flex justify-between"><span>{{ ucfirst($vp->metodo_pago) }}:</span> <span class="font-medium">${{ number_format($vp->total, 2) }}</span></p>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Productos más vendidos -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-5 border-b"><h2 class="font-semibold">Top Productos Vendidos</h2></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-500">Producto</th>
                    <th class="px-4 py-2 text-center text-gray-500">Unidades</th>
                    <th class="px-4 py-2 text-right text-gray-500">Ingresos</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($productosMasVendidos as $pmv)
                <tr>
                    <td class="px-4 py-2">{{ $pmv->nombre }}</td>
                    <td class="px-4 py-2 text-center font-medium">{{ $pmv->total_vendido }}</td>
                    <td class="px-4 py-2 text-right font-medium text-green-600">${{ number_format($pmv->ingresos, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Sin ventas de productos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Alertas de Stock -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-5 border-b"><h2 class="font-semibold text-red-600">⚠️ Productos con Stock Bajo (≤ 5)</h2></div>
        <div class="p-4 space-y-3 max-h-80 overflow-y-auto">
            @forelse($productosStockBajo as $psb)
            <div class="flex justify-between items-center p-2 bg-red-50 rounded">
                <span class="text-sm">{{ $psb->nombre }}</span>
                <span class="font-bold text-red-600 text-sm">{{ $psb->stock }} uds</span>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-4">Todo el inventario está saludable.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Tabla detalle diario -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-5 border-b"><h2 class="font-semibold">Desglose Diario del Período</h2></div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-gray-500">Fecha</th>
                <th class="px-4 py-2 text-right text-gray-500">Productos</th>
                <th class="px-4 py-2 text-right text-gray-500">Membresías</th>
                <th class="px-4 py-2 text-center text-gray-500">Transacciones</th>
                <th class="px-4 py-2 text-right text-gray-500 font-bold">Total Día</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($ventasDiarias as $vd)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($vd->fecha)->format('d/m/Y') }}</td>
                <td class="px-4 py-2 text-right">${{ number_format($vd->productos_dia, 2) }}</td>
                <td class="px-4 py-2 text-right">${{ number_format($vd->membresias_dia, 2) }}</td>
                <td class="px-4 py-2 text-center">{{ $vd->cantidad }}</td>
                <td class="px-4 py-2 text-right font-bold text-green-700">${{ number_format($vd->total_dia, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Sin datos para este período.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection