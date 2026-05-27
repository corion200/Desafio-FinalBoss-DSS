<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Rutina;
use App\Models\Ejercicio;
use App\Models\Cliente;
use App\Http\Requests\RutinaRequest;
use App\Http\Requests\EjercicioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RutinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:coach');
    }

    /**
     * Crear rutina para un cliente
     */
    public function create(Cliente $cliente)
    {
        // Verificar que el cliente está asignado a este coach
        if ($cliente->coach_id !== Auth::id()) {
            abort(403, 'Este cliente no está asignado a ti.');
        }

        return view('coach.rutinas.create', compact('cliente'));
    }

    /**
     * Guardar rutina con ejercicios
     */
    public function store(RutinaRequest $rutinaRequest, EjercicioRequest $ejercicioRequest, Cliente $cliente)
    {
        if ($cliente->coach_id !== Auth::id()) {
            abort(403, 'Este cliente no está asignado a ti.');
        }

        DB::transaction(function () use ($rutinaRequest, $ejercicioRequest, $cliente) {
            // Desactivar rutinas anteriores del cliente
            $cliente->rutinas()->activas()->update(['activa' => false]);

            // Crear la nueva rutina
            $rutina = Rutina::create([
                'cliente_id'    => $cliente->id,
                'coach_id'      => Auth::id(),
                'nombre'        => $rutinaRequest->nombre,
                'objetivo'      => $rutinaRequest->objetivo,
                'observaciones' => $rutinaRequest->observaciones,
                'activa'        => true,
            ]);

            // Crear los ejercicios
            foreach ($ejercicioRequest->ejercicios as $ejercicioData) {
                Ejercicio::create([
                    'rutina_id'        => $rutina->id,
                    'nombre'           => $ejercicioData['nombre'],
                    'grupo_muscular'   => $ejercicioData['grupo_muscular'],
                    'series'           => $ejercicioData['series'],
                    'repeticiones'     => $ejercicioData['repeticiones'],
                    'descanso_segundos'=> $ejercicioData['descanso_segundos'] ?? null,
                    'dia_semana'       => $ejercicioData['dia_semana'],
                    'notas'            => $ejercicioData['notas'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('coach.clientes.show', $cliente)
            ->with('success', 'Rutina creada exitosamente.');
    }

    /**
     * Formulario de edición de rutina
     */
    public function edit(Rutina $rutina)
    {
        if ($rutina->coach_id !== Auth::id()) {
            abort(403, 'No puedes editar una rutina que no te pertenece.');
        }

        $rutina->load('ejercicios');
        $cliente = $rutina->cliente;

        return view('coach.rutinas.edit', compact('rutina', 'cliente'));
    }

    /**
     * Actualizar rutina con ejercicios
     */
    public function update(RutinaRequest $rutinaRequest, EjercicioRequest $ejercicioRequest, Rutina $rutina)
    {
        if ($rutina->coach_id !== Auth::id()) {
            abort(403, 'No puedes editar una rutina que no te pertenece.');
        }

        DB::transaction(function () use ($rutinaRequest, $ejercicioRequest, $rutina) {
            // Actualizar datos de la rutina
            $rutina->update([
                'nombre'        => $rutinaRequest->nombre,
                'objetivo'      => $rutinaRequest->objetivo,
                'observaciones' => $rutinaRequest->observaciones,
            ]);

            // Eliminar ejercicios anteriores y crear nuevos
            $rutina->ejercicios()->delete();

            foreach ($ejercicioRequest->ejercicios as $ejercicioData) {
                Ejercicio::create([
                    'rutina_id'        => $rutina->id,
                    'nombre'           => $ejercicioData['nombre'],
                    'grupo_muscular'   => $ejercicioData['grupo_muscular'],
                    'series'           => $ejercicioData['series'],
                    'repeticiones'     => $ejercicioData['repeticiones'],
                    'descanso_segundos'=> $ejercicioData['descanso_segundos'] ?? null,
                    'dia_semana'       => $ejercicioData['dia_semana'],
                    'notas'            => $ejercicioData['notas'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('coach.clientes.show', $rutina->cliente)
            ->with('success', 'Rutina actualizada exitosamente.');
    }
}