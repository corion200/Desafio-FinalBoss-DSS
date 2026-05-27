{{-- Partial: _ejercicio_row.blade.php --}}
{{-- Variables: $index (int), $ejercicio (Ejercicio|null) --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    <div class="col-span-2">
        <label class="block text-xs font-medium text-gray-600 mb-1">Ejercicio <span class="text-red-500">*</span></label>
        <input type="text" name="ejercicios[{{ $index }}][nombre]"
            value="{{ old("ejercicios.{$index}.nombre", $ejercicio->nombre ?? '') }}"
            placeholder="Ej: Press de banca"
            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Grupo Muscular <span class="text-red-500">*</span></label>
        @php $gm = old("ejercicios.{$index}.grupo_muscular", $ejercicio->grupo_muscular ?? ''); @endphp
        <select name="ejercicios[{{ $index }}][grupo_muscular]"
            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
            <option value="">Seleccionar...</option>
            @foreach(['Pecho','Espalda','Hombros','Bíceps','Tríceps','Piernas','Glúteos','Abdomen','Cardio','Cuerpo completo'] as $g)
                <option value="{{ $g }}" @if($gm === $g) selected @endif>{{ $g }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Día <span class="text-red-500">*</span></label>
        @php $dia = old("ejercicios.{$index}.dia_semana", $ejercicio->dia_semana ?? ''); @endphp
        <select name="ejercicios[{{ $index }}][dia_semana]"
            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
            <option value="">Seleccionar...</option>
            @foreach(['lunes'=>'Lunes','martes'=>'Martes','miercoles'=>'Miércoles','jueves'=>'Jueves','viernes'=>'Viernes','sabado'=>'Sábado','domingo'=>'Domingo'] as $val=>$label)
                <option value="{{ $val }}" @if($dia === $val) selected @endif>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Series <span class="text-red-500">*</span></label>
        <input type="number" name="ejercicios[{{ $index }}][series]" min="1" max="20"
            value="{{ old("ejercicios.{$index}.series", $ejercicio->series ?? 3) }}"
            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Repeticiones <span class="text-red-500">*</span></label>
        <input type="number" name="ejercicios[{{ $index }}][repeticiones]" min="1" max="100"
            value="{{ old("ejercicios.{$index}.repeticiones", $ejercicio->repeticiones ?? 10) }}"
            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none" required>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Descanso (seg.)</label>
        <input type="number" name="ejercicios[{{ $index }}][descanso_segundos]" min="0" placeholder="60"
            value="{{ old("ejercicios.{$index}.descanso_segundos", $ejercicio->descanso_segundos ?? '') }}"
            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
        <input type="text" name="ejercicios[{{ $index }}][notas]" placeholder="Indicaciones..."
            value="{{ old("ejercicios.{$index}.notas", $ejercicio->notas ?? '') }}"
            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none">
    </div>
</div>
