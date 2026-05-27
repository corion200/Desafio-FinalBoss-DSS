@extends('layouts.app')

@section('title', 'Editar Rutina')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-2">Editar Rutina: {{ $rutina->nombre }}</h1>
<p class="text-gray-500 mb-6">Cliente: {{ $rutina->cliente->nombreCompleto() }}</p>

<form method="POST" action="{{ route('coach.rutinas.update', $rutina) }}">
    @csrf @method('PUT')

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <!-- Datos Generales -->
    <div class="bg-white rounded-lg shadow p-5 mb-6">
        <h2 class="font-semibold mb-4">Datos Generales</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Rutina *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $rutina->nombre) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo</label>
                <input type="text" name="objetivo" value="{{ old('objetivo', $rutina->objetivo) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
            <textarea name="observaciones" rows="2" class="w-full px-3 py-2 border rounded-lg">{{ old('observaciones', $rutina->observaciones) }}</textarea>
        </div>
    </div>

    <!-- Ejercicios Actuales -->
    <div class="bg-white rounded-lg shadow p-5 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold">Ejercicios</h2>
            <button type="button" onclick="agregarEjercicio()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition">+ Agregar Ejercicio</button>
        </div>

        <div id="contenedor-ejercicios" class="space-y-4">
            @foreach($rutina->ejercicios as $index => $ej)
            <div class="p-4 bg-gray-50 rounded-lg border ejercicio-fila">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                    <div class="md:col-span-2">
                        <input type="text" name="ejercicios[{{ $index }}][nombre]" value="{{ $ej->nombre }}" placeholder="Nombre del ejercicio" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                    </div>
                    <div>
                        <select name="ejercicios[{{ $index }}][dia_semana]" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                            <option value="">Día...</option>
                            @php $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']; @endphp
                            @foreach($dias as $dia)
                                <option value="{{ $dia }}" {{ $ej->dia_semana === $dia ? 'selected' : '' }}>{{ ucfirst($dia) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <div>
                        <input type="text" name="ejercicios[{{ $index }}][grupo_muscular]" value="{{ $ej->grupo_muscular }}" placeholder="Grupo Muscular" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                    </div>
                    <div>
                        <input type="number" name="ejercicios[{{ $index }}][series]" value="{{ $ej->series }}" placeholder="Series" class="w-full px-3 py-2 border rounded-lg text-sm" min="1" required>
                    </div>
                    <div>
                        <input type="number" name="ejercicios[{{ $index }}][repeticiones]" value="{{ $ej->repeticiones }}" placeholder="Reps" class="w-full px-3 py-2 border rounded-lg text-sm" min="1" required>
                    </div>
                    <div>
                        <input type="number" name="ejercicios[{{ $index }}][descanso_segundos]" value="{{ $ej->descanso_segundos }}" placeholder="Descanso (seg)" class="w-full px-3 py-2 border rounded-lg text-sm" min="0">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="eliminarEjercicio(this)" class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2 rounded-lg text-sm transition">Quitar</button>
                    </div>
                </div>
                <div class="mt-2">
                    <input type="text" name="ejercicios[{{ $index }}][notas]" value="{{ $ej->notas }}" placeholder="Notas opcionales" class="w-full px-3 py-2 border rounded-lg text-sm">
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex space-x-4">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">Actualizar Rutina</button>
        <a href="{{ route('coach.clientes.show', $rutina->cliente) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition">Cancelar</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Empezar el contador desde el último índice del foreach
    let index = {{ $rutina->ejercicios->count() }};

    function agregarEjercicio() {
        const contenedor = document.getElementById('contenedor-ejercicios');
        const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        let optionsHtml = '<option value="">Día...</option>';
        dias.forEach(d => optionsHtml += `<option value="${d}">${d.charAt(0).toUpperCase() + d.slice(1)}</option>`);

        const html = `
        <div class="p-4 bg-gray-50 rounded-lg border ejercicio-fila">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                <div class="md:col-span-2">
                    <input type="text" name="ejercicios[${index}][nombre]" placeholder="Nombre del ejercicio" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                </div>
                <div>
                    <select name="ejercicios[${index}][dia_semana]" class="w-full px-3 py-2 border rounded-lg text-sm" required>
                        ${optionsHtml}
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div><input type="text" name="ejercicios[${index}][grupo_muscular]" placeholder="Grupo Muscular" class="w-full px-3 py-2 border rounded-lg text-sm" required></div>
                <div><input type="number" name="ejercicios[${index}][series]" placeholder="Series" class="w-full px-3 py-2 border rounded-lg text-sm" min="1" required></div>
                <div><input type="number" name="ejercicios[${index}][repeticiones]" placeholder="Reps" class="w-full px-3 py-2 border rounded-lg text-sm" min="1" required></div>
                <div><input type="number" name="ejercicios[${index}][descanso_segundos]" placeholder="Descanso (seg)" class="w-full px-3 py-2 border rounded-lg text-sm" min="0"></div>
                <div class="flex items-end"><button type="button" onclick="eliminarEjercicio(this)" class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2 rounded-lg text-sm transition">Quitar</button></div>
            </div>
            <div class="mt-2"><input type="text" name="ejercicios[${index}][notas]" placeholder="Notas opcionales" class="w-full px-3 py-2 border rounded-lg text-sm"></div>
        </div>`;
        contenedor.insertAdjacentHTML('beforeend', html);
        index++;
    }
    function eliminarEjercicio(btn) {
        if(confirm('¿Eliminar este ejercicio?')) {
            btn.closest('.ejercicio-fila').remove();
        }
    }
</script>
@endpush