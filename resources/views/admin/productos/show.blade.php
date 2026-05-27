@extends('layouts.app')

@section('title', 'Detalle de Producto')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">{{ $producto->nombre }}</h1>
    <div class="space-x-2">
        <a href="{{ route('admin.productos.edit', $producto) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition">Editar</a>
        <a href="{{ route('admin.productos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">Volver</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <!-- Info Producto -->
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm space-y-2 text-gray-600">
                <p><strong>Categoría:</strong> <span class="px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ ucfirst($producto->categoria) }}</span></p>
                <p><strong>Precio:</strong> <span class="text-lg font-bold text-gray-800">${{ number_format($producto->precio, 2) }}</span></p>
                <p><strong>Stock Actual:</strong> <span class="text-lg font-bold @if($producto->stock <= 0) text-red-600 @else text-green-600 @endif">{{ $producto->stock }} unidades</span></p>
                <p class="text-gray-500 italic">{{ $producto->descripcion ?? 'Sin descripción' }}</p>
            </div>
        </div>

        <!-- Formulario Reponer Stock -->
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
            <h2 class="font-semibold mb-3">Reponer Stock</h2>
            <form method="POST" action="{{ route('admin.productos.reponer-stock', $producto) }}">
                @csrf
                @if($errors->has('cantidad'))
                    <p class="text-xs text-red-600 mb-2">{{ $errors->first('cantidad') }}</p>
                @endif
                <div class="flex gap-2">
                    <input type="number" name="cantidad" min="1" placeholder="Cant. a agregar" class="flex-1 px-3 py-2 border rounded-lg text-sm" required>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <!-- Estadísticas -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-5 text-center">
                <p class="text-sm text-gray-500">Total Vendido (Unidades)</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $totalVendido }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-5 text-center">
                <p class="text-sm text-gray-500">Ingresos Generados</p>
                <p class="text-3xl font-bold text-green-600">${{ number_format($ingresosGenerados, 2) }}</p>
            </div>
        </div>

        <!-- Últimas Ventas de este producto -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-5 border-b"><h2 class="font-semibold">Últimas Ventas de este Producto</h2></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-500">Fecha</th>
                        <th class="px-4 py-2 text-left text-gray-500">Cliente</th>
                        <th class="px-4 py-2 text-left text-gray-500">Vendedor</th>
                        <th class="px-4 py-2 text-center text-gray-500">Cant.</th>
                        <th class="px-4 py-2 text-right text-gray-500">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($ultimasVentas as $uv)
                    <tr>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($uv->created_at)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $uv->cliente_nombre ? "$uv->cliente_nombre $uv->cliente_apellido" : 'Público' }}</td>
                        <td class="px-4 py-2">{{ $uv->vendedor }}</td>
                        <td class="px-4 py-2 text-center">{{ $uv->cantidad }}</td>
                        <td class="px-4 py-2 text-right font-medium">${{ number_format($uv->subtotal, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Sin ventas de este producto aún.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection