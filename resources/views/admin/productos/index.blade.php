@extends('layouts.app')

@section('title', 'Detalle de Venta')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Detalle de Venta #{{ $venta->id }}</h1>
    <a href="{{ route('ventas.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">Volver</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold border-b pb-2 mb-3">Información</h2>
        <div class="text-sm space-y-2 text-gray-600">
            <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Cliente:</strong> {{ $venta->cliente?->nombreCompleto() ?? 'Público general' }}</p>
            <p><strong>Vendedor:</strong> {{ $venta->usuario->name }}</p>
            <p><strong>Método de Pago:</strong> {{ ucfirst($venta->metodo_pago) }}</p>
            @if($venta->notas)
                <p><strong>Notas:</strong> {{ $venta->notas }}</p>
            @endif
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-500">Producto</th>
                    <th class="px-4 py-3 text-center text-gray-500">Cantidad</th>
                    <th class="px-4 py-3 text-right text-gray-500">P. Unitario</th>
                    <th class="px-4 py-3 text-right text-gray-500">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($venta->detalles as $detalle)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $detalle->producto->nombre }}</td>
                    <td class="px-4 py-3 text-center">{{ $detalle->cantidad }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="px-4 py-3 text-right font-medium">${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 border-t-2 border-gray-800">
                    <td colspan="3" class="px-4 py-3 text-right font-bold text-lg">TOTAL A PAGAR:</td>
                    <td class="px-4 py-3 text-right font-bold text-lg text-green-700">${{ number_format($venta->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection