@extends('layouts.app')

@section('title', 'Dashboard Recepcionista')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Panel de Recepción</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
        <p class="text-sm text-gray-500">Clientes Activos</p>
        <p class="text-3xl font-bold text-blue-600">{{ $clientesActivos }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Con Membresía Activa</p>
        <p class="text-3xl font-bold text-green-600">{{ $clientesConMembresia }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
        <p class="text-sm text-gray-500">Membresías Vendidas Hoy</p>
        <p class="text-3xl font-bold text-purple-600">{{ $membresiasVendidasHoy }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <a href="{{ route('ventas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow p-6 flex items-center justify-between transition">
        <div>
            <p class="font-bold text-lg">Nueva Venta de Productos</p>
            <p class="text-blue-200 text-sm">Registrar venta de suplementos o equipo</p>
        </div>
        <span class="text-3xl">🛒</span>
    </a>
    <a href="{{ route('clientes.create') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg shadow p-6 flex items-center justify-between transition">
        <div>
            <p class="font-bold text-lg">Registrar Nuevo Cliente</p>
            <p class="text-green-200 text-sm">Agregar miembro al gimnasio</p>
        </div>
        <span class="text-3xl">👤</span>
    </a>
</div>

@if($productosSinStock > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
        <p class="text-red-700 font-medium">⚠️ Alerta de Inventario: Hay {{ $productosSinStock }} producto(s) sin stock. Notificar al administrador.</p>
    </div>
@endif

<div class="bg-white rounded-lg shadow">
    <div class="p-5 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Últimos Clientes Registrados</h2>
        <a href="{{ route('clientes.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver todos →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Nombre</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Cédula</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Coach</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Membresía</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($ultimosClientes as $cliente)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $cliente->nombreCompleto() }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $cliente->cedula }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $cliente->coach?->name ?? 'Sin asignar' }}</td>
                    <td class="px-4 py-3">
                        @if($cliente->tieneMembresiaActiva())
                            <span class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full">Activa</span>
                        @else
                            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-full">Sin membresía</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No hay clientes</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection