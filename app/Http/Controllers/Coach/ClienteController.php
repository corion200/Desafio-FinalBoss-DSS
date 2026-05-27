<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:coach');
    }

    /**
     * VER MIS CLIENTES ASIGNADOS
     */
    public function index(Request $request)
    {
        $query = Auth::user()->clientes()->activos();

        if ($request->filled('buscar')) {
            $buscar = $request->get('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('apellido', 'LIKE', "%{$buscar}%")
                  ->orWhere('cedula', 'LIKE', "%{$buscar}%");
            });
        }

        $clientes = $query->with(['membresiaClientes' => function ($q) {
            $q->where('estado', 'activa')
              ->where('fecha_fin', '>=', now()->toDateString());
        }, 'membresiaClientes.membresia', 'rutinas'])
            ->orderBy('apellido')
            ->paginate(15);

        return view('coach.clientes.index', compact('clientes'));
    }

    /**
     * VER DETALLE DE UN CLIENTE ASIGNADO
     */
    public function show(Cliente $cliente)
    {
        if ($cliente->coach_id !== Auth::id()) {
            abort(403, 'Este cliente no está asignado a ti.');
        }

        $cliente->load([
            'membresiaClientes.membresia',
            'rutinas.ejercicios',
        ]);

        $rutinaActiva = $cliente->rutinas()->activas()->with('ejercicios')->first();

        return view('coach.clientes.show', compact('cliente', 'rutinaActiva'));
    }
}