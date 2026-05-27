@extends('layouts.app')

@section('title', 'Crear Rutina')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Crear Rutina</h1>
        <p class="text-sm text-gray-500 mt-1">Cliente: <strong>{{ $cliente->nombreCompleto() }}</strong></p>
    </div>
    <a href="{{ route('coach.clientes.show', $cliente) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
        ← Volver
    </a>
</div>

@if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
        <p class="font-bold mb-1">Por favor corrige los siguientes errores:</p>
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('coach.rutinas.store', $cliente) }}" id="rutina-form">
    @csrf

    {{-- Datos de la Rutina --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Datos de la Rutina</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Rutina <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                    placeholder="Ej: Rutina de Fuerza A"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                    required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo</label>
                <input type="text" name="objetivo" value="{{ old('objetivo') }}"
                    placeholder="Ej: Aumento de masa muscular"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea name="observaciones" rows="2"
                    placeholder="Notas generales para el cliente..."
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">{{ old('observaciones') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Ejercicios Dinámicos --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Ejercicios</h2>
            <button type="button" id="agregar-ejercicio"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Agregar Ejercicio
            </button>
        </div>

        <div id="ejercicios-container" class="space-y-4">
            {{-- Fila inicial --}}
            <div class="ejercicio-row bg-gray-50 border border-gray-200 rounded-lg p-4 relative">
                <button type="button" class="remove-ejercicio absolute top-3 right-3 text-red-400 hover:text-red-600 text-lg font-bold leading-none" title="Eliminar">×</button>
                @include('coach.rutinas._ejercicio_row', ['index' => 0, 'ejercicio' => null])
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('coach.clientes.show', $cliente) }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">Cancelar</a>
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm">
            Guardar Rutina
        </button>
    </div>
</form>

@push('scripts')
<script>
    let ejercicioIndex = 1;

    const rowTemplate = (index) => `
        <div class="ejercicio-row bg-gray-50 border border-gray-200 rounded-lg p-4 relative">
            <button type="button" class="remove-ejercicio absolute top-3 right-3 text-red-400 hover:text-red-600 text-lg font-bold leading-none" title="Eliminar">×</button>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Ejercicio *</label>
                    <input type="text" name="ejercicios[${index}][nombre]" placeholder="Ej: Press de banca"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Grupo Muscular *</label>
                    <select name="ejercicios[${index}][grupo_muscular]"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
                        <option value="">Seleccionar...</option>
                        <option value="Pecho">Pecho</option>
                        <option value="Espalda">Espalda</option>
                        <option value="Hombros">Hombros</option>
                        <option value="Bíceps">Bíceps</option>
                        <option value="Tríceps">Tríceps</option>
                        <option value="Piernas">Piernas</option>
                        <option value="Glúteos">Glúteos</option>
                        <option value="Abdomen">Abdomen</option>
                        <option value="Cardio">Cardio</option>
                        <option value="Cuerpo completo">Cuerpo completo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Día *</label>
                    <select name="ejercicios[${index}][dia_semana]"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
                        <option value="">Seleccionar...</option>
                        <option value="lunes">Lunes</option>
                        <option value="martes">Martes</option>
                        <option value="miercoles">Miércoles</option>
                        <option value="jueves">Jueves</option>
                        <option value="viernes">Viernes</option>
                        <option value="sabado">Sábado</option>
                        <option value="domingo">Domingo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Series *</label>
                    <input type="number" name="ejercicios[${index}][series]" min="1" max="20" value="3"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Repeticiones *</label>
                    <input type="number" name="ejercicios[${index}][repeticiones]" min="1" max="100" value="10"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Descanso (seg.)</label>
                    <input type="number" name="ejercicios[${index}][descanso_segundos]" min="0" placeholder="60"
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
                    <input type="text" name="ejercicios[${index}][notas]" placeholder="Indicaciones..."
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none">
                </div>
            </div>
        </div>`;

    document.getElementById('agregar-ejercicio').addEventListener('click', function () {
        const container = document.getElementById('ejercicios-container');
        container.insertAdjacentHTML('beforeend', rowTemplate(ejercicioIndex++));
        bindRemoveButtons();
    });

    function bindRemoveButtons() {
        document.querySelectorAll('.remove-ejercicio').forEach(btn => {
            btn.onclick = function () {
                const rows = document.querySelectorAll('.ejercicio-row');
                if (rows.length > 1) {
                    this.closest('.ejercicio-row').remove();
                } else {
                    alert('Debe haber al menos un ejercicio.');
                }
            };
        });
    }

    bindRemoveButtons();
</script>
@endpush
@endsection
