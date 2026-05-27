@extends('layouts.app')

@section('title', 'Historial de Ventas')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Historial de Ventas de Productos</h1>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('ventas.index') }}" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Desde</label>
            <input type="date" name="desde" value="{{ request('desde') }}" class="px-3 py-2 border rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Hasta</label>
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="px-3 py-2 border rounded-lg text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Método de Pago</label>
            <select name="metodo_pago" class="px-3 py-2 border rounded-lg text-sm">
                <option value="">Todos</option>
                <option value="efectivo" @if(request('metodo_pago') === 'efectivo') selected @endif>Efectivo</option>
                <option value="tarjeta" @if(request('metodo_pago') === 'tarjeta') selected @endif>Tarjeta</option>
                <option value="transferencia" @if(request('metodo_pago') === 'transferencia') selected @endif>Transferencia</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Filtrar</button>
        <a href="{{ route('ventas.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">Limpiar</a>
    </form>
</div>

<div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg flex justify-between items-center">
    <span class="text-blue-800 font-medium">Total generado en el período:</span>
    <span class="text-2xl font-bold text-blue-800">${{ number_format($totalPeriodo, 2) }}</span>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500">Fecha/Hora</th>
                <th class="px-4 py-3 text-left text-gray-500">Cliente</th>
                <th class="px-4 py-3 text-left text-gray-500">Vendedor</th>
                <th class="px-4 py-3 text-left text-gray-500">Método</th>
                <th class="px-4 py-3 text-right text-gray-500">Total</th>
                <th class="px-4 py-3 text-center text-gray-500">Detalle</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($ventas as $venta)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">{{ $venta->cliente?->nombreCompleto() ?? 'Público general' }}</td>
                <td class="px-4 py-3">{{ $venta->usuario->name }}</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs">{{ ucfirst($venta->metodo_pago) }}</span></td>
                <td class="px-4 py-3 text-right font-bold">${{ number_format($venta->total, 2) }}</td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('ventas.show', $venta) }}" class="text-indigo-600 hover:underline text-sm">Ver</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No hay ventas en este período.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $ventas->withQueryString()->links() }}</div>
@endsection