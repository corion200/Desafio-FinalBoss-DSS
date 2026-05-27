@extends('layouts.app')

@section('title', 'Mis Clientes')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Mis Clientes Asignados</h1>

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('coach.clientes.index') }}" class="flex gap-4">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar cliente..." class="flex-1 px-3 py-2 border rounded-lg text-sm">
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Buscar</button>
        <a href="{{ route('coach.clientes.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">Limpiar</a>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500">Nombre</th>
                <th class="px-4 py-3 text-left text-gray-500">Teléfono</th>
                <th class="px-4 py-3 text-left text-gray-500">Membresía</th>
                <th class="px-4 py-3 text-left text-gray-500">Rutina</th>
                <th class="px-4 py-3 text-center text-gray-500">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($clientes as $cliente)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $cliente->nombreCompleto() }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $cliente->telefono }}</td>
                <td class="px-4 py-3">
                    @if($cliente->membresiaClientes->count() > 0)
                        <span class="text-green-600 font-medium">Vigente</span>
                    @else
                        <span class="text-red-500">Vencida</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($cliente->rutinas->where('activa', true)->count() > 0)
                        <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs">Activa</span>
                    @else
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs">Sin rutina</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('coach.clientes.show', $cliente) }}" class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded text-xs hover:bg-indigo-200 transition">Ver / Rutina</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No tienes clientes asignados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $clientes->withQueryString()->links() }}</div>
@endsection