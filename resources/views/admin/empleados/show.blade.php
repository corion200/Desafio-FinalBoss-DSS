@extends('layouts.app')

@section('title', 'Detalle de Empleado')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Detalle de Empleado: {{ $empleado->name }}</h1>
    <div class="space-x-2">
        <a href="{{ route('admin.empleados.edit', $empleado) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition">Editar</a>
        <a href="{{ route('admin.empleados.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">Volver</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <!-- Info Principal -->
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="font-semibold text-gray-800 border-b pb-2 mb-3">Ficha de Empleado</h2>
            <div class="text-sm space-y-3 text-gray-600">
                <p><strong>Nombre:</strong> <span class="text-gray-900">{{ $empleado->name }}</span></p>
                <p><strong>Correo Electrónico:</strong> <span class="text-gray-900">{{ $empleado->email }}</span></p>
                <p><strong>Teléfono:</strong> <span class="text-gray-900">{{ $empleado->telefono ?? 'No registrado' }}</span></p>
                <p>
                    <strong>Puesto:</strong> 
                    @if($empleado->role === 'coach')
                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Coach</span>
                    @else
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Recepcionista</span>
                    @endif
                </p>
                <p>
                    <strong>Estado:</strong> 
                    @if($empleado->activo)
                        <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-semibold border border-green-200">Activo</span>
                    @else
                        <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs font-semibold border border-red-200">Inactivo</span>
                    @endif
                </p>
                <p><strong>Fecha de Ingreso:</strong> <span class="text-gray-900">{{ $empleado->created_at->format('d/m/Y') }}</span></p>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        @if($empleado->activo)
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
            <h3 class="font-semibold text-gray-850 mb-2 text-sm">Suspender / Desactivar</h3>
            <p class="text-xs text-gray-500 mb-3">Al desactivar a este empleado, no podrá iniciar sesión ni realizar acciones en el sistema.</p>
            <form method="POST" action="{{ route('admin.empleados.destroy', $empleado) }}" 
                onsubmit="return confirm('¿Está seguro de suspender esta cuenta?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm transition">
                    Desactivar Cuenta
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Estadísticas de Desempeño -->
    <div class="lg:col-span-2 space-y-6">
        <h2 class="text-lg font-bold text-gray-800">Actividad del Empleado</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($empleado->role === 'coach')
                <!-- Estadísticas para Coach -->
                <div class="bg-white rounded-lg shadow p-5 text-center">
                    <p class="text-sm text-gray-500">Clientes Asignados Activos</p>
                    <p class="text-4xl font-bold text-purple-600 mt-2">{{ $empleado->clientes_count }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-5 text-center">
                    <p class="text-sm text-gray-500">Reportes de Equipo Realizados</p>
                    <p class="text-4xl font-bold text-orange-600 mt-2">{{ $empleado->reportes_equipo_count }}</p>
                </div>
            @else
                <!-- Estadísticas para Recepcionista -->
                <div class="bg-white rounded-lg shadow p-5 text-center">
                    <p class="text-sm text-gray-500">Ventas de Productos Registradas</p>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $empleado->ventas_count }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-5 text-center">
                    <p class="text-sm text-gray-500">Membresías Vendidas / Renovadas</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">
                        {{ $empleado->membresiasVendidas()->count() }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Tarjeta de advertencia si está inactivo -->
        @if(!$empleado->activo)
            <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                <h4 class="font-bold text-sm">Cuenta Desactivada</h4>
                <p class="text-xs mt-1">Este empleado no tiene permitido ingresar al sistema. Si deseas restablecer su acceso, haz clic en <strong>Editar</strong> y cambia su estado a Activo.</p>
            </div>
        @endif
    </div>
</div>
@endsection
