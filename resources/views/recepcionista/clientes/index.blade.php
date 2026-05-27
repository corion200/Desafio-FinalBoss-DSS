@extends('layouts.app')

@section('title', 'Listado de Clientes')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Clientes</h1>
    <a href="{{ route('clientes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">+ Nuevo Cliente</a>
</div>

<!-- Filtros de Búsqueda -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('clientes.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, cédula o teléfono..." class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <select name="membresia" class="px-3 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todas las membresías</option>
                <option value="activa" @if(request('membresia') === 'activa') selected @endif>Con membresía activa</option>
                <option value="sin_membresia" @if(request('membresia') === 'sin_membresia') selected @endif>Sin membresía</option>
            </select>
        </div>
        <div>
            <select name="coach_id" class="px-3 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos los coaches</option>
                @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}" @if(request('coach_id') == $coach->id) selected @endif>{{ $coach->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">Filtrar</button>
        <a href="{{ route('clientes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">Limpiar</a>
    </form>
</div>

<!-- Tabla de Resultados -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Nombre Completo</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Cédula</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Teléfono</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Coach</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Membresía</th>
                <th class="px-4 py-3 text-center text-gray-500 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($clientes as $cliente)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-blue-600">
                    <a href="{{ route('clientes.show', $cliente) }}">{{ $cliente->nombreCompleto() }}</a>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $cliente->cedula }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $cliente->telefono }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $cliente->coach?->name ?? '<span class="text-gray-400">N/A</span>' }}</td>
                <td class="px-4 py-3">
                    @if($cliente->tieneMembresiaActiva())
                        <span class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full">Activa</span>
                    @else
                        <span class="px-2 py-0.5 text-xs bg-red-100 text-red-600 rounded-full">Inactiva</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center space-x-2">
                    <a href="{{ route('clientes.show', $cliente) }}" class="text-indigo-600 hover:underline">Ver</a>
                    <a href="{{ route('clientes.edit', $cliente) }}" class="text-yellow-600 hover:underline">Editar</a>
                    <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400">No se encontraron clientes con los filtros aplicados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="mt-6 flex justify-center">
    {{ $clientes->withQueryString()->links() }}
</div>
@endsection