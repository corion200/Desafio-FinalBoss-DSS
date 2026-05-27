@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Editar Empleado: {{ $empleado->name }}</h1>
    <a href="{{ route('admin.empleados.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
        ← Volver
    </a>
</div>

<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.empleados.update', $empleado) }}">
        @csrf
        @method('PUT')

        <!-- Mostrar Errores de Validación -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                <p class="font-bold mb-2">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-5">
            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $empleado->name) }}" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" 
                    required>
            </div>

            <!-- Email y Teléfono -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $empleado->email) }}" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" 
                        required>
                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $empleado->telefono) }}" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" 
                        placeholder="Ej: 1234-5678">
                </div>
            </div>

            <!-- Puesto y Estado de Activación -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Puesto <span class="text-red-500">*</span></label>
                    <select id="role" name="role" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" required>
                        <option value="recepcionista" @if(old('role', $empleado->role) === 'recepcionista') selected @endif>Recepcionista</option>
                        <option value="coach" @if(old('role', $empleado->role) === 'coach') selected @endif>Coach</option>
                    </select>
                </div>
                <div>
                    <label for="activo" class="block text-sm font-medium text-gray-700 mb-1">Estado de Acceso <span class="text-red-500">*</span></label>
                    <select id="activo" name="activo" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" required>
                        <option value="1" @selected(old('activo', $empleado->activo) == 1)>Permitido (Activo)</option>
                        <option value="0" @selected(old('activo', $empleado->activo) == 0)>Suspendido (Inactivo)</option>
                    </select>
                </div>
            </div>

            <!-- Contraseña de Acceso (Opcional) -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Cambiar Contraseña (Opcional)</label>
                <input type="password" id="password" name="password" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition" 
                    placeholder="Dejar en blanco para conservar la contraseña actual">
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end pt-4 border-t space-x-4">
                <a href="{{ route('admin.empleados.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
