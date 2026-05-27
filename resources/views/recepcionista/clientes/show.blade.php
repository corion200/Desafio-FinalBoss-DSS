@extends('layouts.app')

@section('title', 'Detalle de Cliente')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">{{ $cliente->nombreCompleto() }}</h1>
    <div class="space-x-2">
        <a href="{{ route('clientes.edit', $cliente) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition">Editar Datos</a>
        <a href="{{ route('clientes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">Volver</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Izquierda: Info y Membresía -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Info Personal -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="font-semibold text-gray-800 border-b pb-2 mb-3">Información Personal</h2>
            <div class="text-sm space-y-2 text-gray-600">
                <p><strong>Cédula:</strong> {{ $cliente->cedula }}</p>
                <p><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
                <p><strong>Email:</strong> {{ $cliente->email ?? 'N/A' }}</p>
                <p><strong>Fecha Nac.:</strong> {{ $cliente->fecha_nacimiento?->format('d/m/Y') ?? 'N/A' }}</p>
                <p><strong>Coach:</strong> {{ $cliente->coach?->name ?? 'Sin asignar' }}</p>
            </div>
        </div>

        <!-- Gestión de Membresía -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="font-semibold text-gray-800 border-b pb-2 mb-3">Membresía</h2>
            @if($membresiaActiva)
                <div class="bg-green-50 p-3 rounded-lg mb-3">
                    <p class="font-bold text-green-800">{{ $membresiaActiva->membresia->nombre }}</p>
                    <p class="text-sm text-green-700">Vence: {{ $membresiaActiva->fecha_fin->format('d/m/Y') }}</p>
                    <p class="text-sm text-green-700">Pagado: ${{ number_format($membresiaActiva->precio_pagado, 2) }}</p>
                </div>
                <form method="POST" action="{{ route('clientes.cancelar-membresia', $cliente) }}" onsubmit="return confirm('¿Cancelar esta membresía?');">
                    @csrf
                    <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2 rounded-lg text-sm transition">Cancelar Membresía</button>
                </form>
            @else
                <p class="text-sm text-red-600 mb-3">Actualmente sin membresía activa.</p>
                <form method="POST" action="{{ route('clientes.vender-membresia', $cliente) }}">
                    @csrf
                    @if($errors->has('membresia_id'))
                        <p class="text-xs text-red-600 mb-2">{{ $errors->first('membresia_id') }}</p>
                    @endif
                    <select name="membresia_id" class="w-full px-3 py-2 border rounded-lg text-sm mb-2" required>
                        <option value="">Seleccionar Membresía...</option>
                        @foreach($membresias as $memb)
                            <option value="{{ $memb->id }}">{{ $memb->nombre }} ({{ $memb->duracion_dias }}d) - ${{ number_format($memb->precio, 2) }}</option>
                        @endforeach
                    </select>
                    <select name="metodo_pago" class="w-full px-3 py-2 border rounded-lg text-sm mb-2" required>
                        <option value="">Método de Pago...</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm transition">Vender Membresía</button>
                </form>
            @endif
        </div>

        <!-- Asignar Coach -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="font-semibold text-gray-800 border-b pb-2 mb-3">Asignar / Cambiar Coach</h2>
            @if($cliente->coach_id)
                <p class="text-sm mb-2">Coach actual: <strong>{{ $cliente->coach->name }}</strong></p>
            @endif
            <form method="POST" action="{{ route('clientes.asignar-coach', $cliente) }}">
                @csrf
                <select name="coach_id" class="w-full px-3 py-2 border rounded-lg text-sm mb-2" required>
                    <option value="">Seleccionar Coach...</option>
                    @foreach($coaches as $coach)
                        <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg text-sm transition">Asignar Coach</button>
            </form>
            @if($cliente->coach_id)
                <form method="POST" action="{{ route('clientes.quitar-coach', $cliente) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg text-sm transition">Quitar Coach</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Columna Derecha: Historial -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Historial de Membresías -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-5 border-b"><h2 class="font-semibold text-gray-800">Historial de Membresías</h2></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Tipo</th>
                        <th class="px-4 py-2 text-left">Inicio</th>
                        <th class="px-4 py-2 text-left">Fin</th>
                        <th class="px-4 py-2 text-left">Precio</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($historialMembresias as $hist)
                    <tr>
                        <td class="px-4 py-2">{{ $hist->membresia->nombre }}</td>
                        <td class="px-4 py-2">{{ $hist->fecha_inicio->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $hist->fecha_fin->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">${{ number_format($hist->precio_pagado, 2) }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-0.5 text-xs rounded-full 
                                @if($hist->estado === 'activa') bg-green-100 text-green-700 
                                @elseif($hist->estado === 'cancelada') bg-red-100 text-red-700 
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($hist->estado) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400">Sin historial</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Compras de Productos -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-5 border-b"><h2 class="font-semibold text-gray-800">Compras de Productos</h2></div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Fecha</th>
                        <th class="px-4 py-2 text-left">Producto(s)</th>
                        <th class="px-4 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($cliente->ventas as $venta)
                    <tr>
                        <td class="px-4 py-2">{{ $venta->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            @foreach($venta->detalles as $det)
                                {{ $det->producto->nombre }} (x{{ $det->cantidad }}) <br>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 text-right font-medium">${{ number_format($venta->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-4 py-4 text-center text-gray-400">Sin compras</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection