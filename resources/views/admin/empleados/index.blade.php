@extends('layouts.app')

@section('title', 'Gestión de Empleados')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestión de Empleados</h1>
    <a href="{{ route('admin.empleados.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
        + Registrar Empleado
    </a>
</div>

<!-- Notificaciones -->
@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg">
        {{ session('success') }}
    </div>
@endif

<!-- Barra de Búsqueda y Filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.empleados.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <!-- Buscar -->
        <div>
            <label for="buscar" class="block text-xs font-medium text-gray-500 mb-1">Buscar</label>
            <input type="text" id="buscar" name="buscar" value="{{ request('buscar') }}" 
                placeholder="Nombre, email o teléfono..." 
                class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-green-500 focus:border-green-500 outline-none">
        </div>

        <!-- Puesto / Rol -->
        <div>
            <label for="role" class="block text-xs font-medium text-gray-500 mb-1">Puesto</label>
            <select id="role" name="role" 
                class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-green-500 focus:border-green-500 outline-none">
                <option value="">Todos</option>
                <option value="recepcionista" @if(request('role') === 'recepcionista') selected @endif>Recepcionista</option>
                <option value="coach" @if(request('role') === 'coach') selected @endif>Coach</option>
            </select>
        </div>

        <!-- Estado -->
        <div>
            <label for="estado" class="block text-xs font-medium text-gray-500 mb-1">Estado</label>
            <select id="estado" name="estado" 
                class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-green-500 focus:border-green-500 outline-none">
                <option value="">Todos</option>
                <option value="activo" @if(request('estado') === 'activo') selected @endif>Activo</option>
                <option value="inactivo" @if(request('estado') === 'inactivo') selected @endif>Inactivo</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Filtrar
            </button>
            @if(request()->anyFilled(['buscar', 'role', 'estado']))
                <a href="{{ route('admin.empleados.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition text-center">
                    Limpiar
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabla de Empleados -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Nombre</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Correo Electrónico</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-600">Puesto</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Teléfono</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-600">Estado</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($empleados as $empleado)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">
                            <a href="{{ route('admin.empleados.show', $empleado) }}" class="hover:text-green-600 transition">
                                {{ $empleado->name }}
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $empleado->email }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($empleado->role === 'coach')
                            <span class="px-2.5 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                Coach
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                Recepcionista
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $empleado->telefono ?? 'N/D' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($empleado->activo)
                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium border border-green-200">
                                Activo
                            </span>
                        @else
                            <span class="px-2 py-1 bg-red-50 text-red-700 rounded-full text-xs font-medium border border-red-200">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('admin.empleados.show', $empleado) }}" 
                                class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                Ver
                            </a>
                            <a href="{{ route('admin.empleados.edit', $empleado) }}" 
                                class="bg-yellow-50 text-yellow-600 hover:bg-yellow-100 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                Editar
                            </a>
                            @if($empleado->activo)
                                <form method="POST" action="{{ route('admin.empleados.destroy', $empleado) }}" class="inline" 
                                    onsubmit="return confirm('¿Está seguro de desactivar a este empleado? Perderá el acceso al sistema.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                        Desactivar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        No se encontraron empleados en el sistema.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($empleados->hasPages())
        <div class="p-4 border-t bg-gray-50">
            {{ $empleados->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
