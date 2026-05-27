@extends('layouts.app')

@section('title', 'Dashboard Coach')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Mi Panel de Coach</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
        <p class="text-sm text-gray-500">Clientes Asignados</p>
        <p class="text-3xl font-bold text-purple-600">{{ $misClientes }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-indigo-500">
        <p class="text-sm text-gray-500">Rutinas Creadas</p>
        <p class="text-3xl font-bold text-indigo-600">{{ $misRutinas }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-500">
        <p class="text-sm text-gray-500">Reportes Pendientes</p>
        <p class="text-3xl font-bold text-orange-600">{{ $misReportes }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <a href="{{ route('coach.clientes.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow p-6 flex items-center justify-between transition">
        <div>
            <p class="font-bold text-lg">Ver Mis Clientes</p>
            <p class="text-purple-200 text-sm">Revisar listado y crear rutinas</p>
        </div>
        <span class="text-3xl">👥</span>
    </a>
    <a href="{{ route('coach.reportes.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white rounded-lg shadow p-6 flex items-center justify-between transition">
        <div>
            <p class="font-bold text-lg">Reportar Equipo Dañado</p>
            <p class="text-orange-200 text-sm">Notificar al administrador</p>
        </div>
        <span class="text-3xl">🔧</span>
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-5 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Mis Clientes Asignados</h2>
        <a href="{{ route('coach.clientes.index') }}" class="text-purple-600 hover:text-purple-800 text-sm">Ver todos →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Nombre</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Membresía</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Rutina Activa</th>
                    <th class="px-4 py-3 text-center text-gray-500 font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($clientesAsignados as $cliente)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $cliente->nombreCompleto() }}</td>
                    <td class="px-4 py-3">
                        @if($cliente->membresiaClientes->count() > 0)
                            <span class="text-green-600 text-xs">Vigente</span>
                        @else
                            <span class="text-red-500 text-xs">Vencida/Sin membresía</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($cliente->rutinas->where('activa', true)->count() > 0)
                            <span class="px-2 py-0.5 text-xs bg-indigo-100 text-indigo-700 rounded-full">Sí</span>
                        @else
                            <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded-full">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('coach.clientes.show', $cliente) }}" class="text-indigo-600 hover:underline text-sm">Ver Detalle</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No tienes clientes asignados aún</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection