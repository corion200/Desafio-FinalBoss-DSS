<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use App\Models\Membresia;
use App\Models\MembresiaCliente;
use App\Models\Venta;
use App\Http\Requests\ClienteRequest;
use App\Http\Requests\MembresiaClienteRequest;
use App\Http\Requests\AsignarCoachRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,recepcionista');
    }

    /**
     * LISTADO DE REGISTROS - Página principal del recurso
     */
    public function index(Request $request)
    {
        // Usando Eloquent con scope y búsqueda
        $query = Cliente::with('coach', 'membresiaClientes');

        // Búsqueda con Query Builder
        if ($request->filled('buscar')) {
            $buscar = $request->get('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('apellido', 'LIKE', "%{$buscar}%")
                  ->orWhere('cedula', 'LIKE', "%{$buscar}%")
                  ->orWhere('telefono', 'LIKE', "%{$buscar}%");
            });
        }

        // Filtro por estado de membresía
        if ($request->filled('membresia')) {
            if ($request->get('membresia') === 'activa') {
                $query->conMembresiaActiva();
            } elseif ($request->get('membresia') === 'sin_membresia') {
                $query->whereDoesntHave('membresiaClientes', function ($q) {
                    $q->where('estado', 'activa')
                      ->where('fecha_fin', '>=', now()->toDateString());
                });
            }
        }

        // Filtro por coach
        if ($request->filled('coach_id')) {
            $query->where('coach_id', $request->get('coach_id'));
        }

        $clientes = $query->activos()->orderBy('apellido')->paginate(15);
        $coaches = User::coaches()->activos()->orderBy('name')->get();

        return view('recepcionista.clientes.index', compact('clientes', 'coaches'));
    }

    /**
     * FORMULARIO DE CREACIÓN
     */
    public function create()
    {
        $coaches = User::coaches()->activos()->orderBy('name')->get();
        return view('recepcionista.clientes.create', compact('coaches'));
    }

    /**
     * CREAR REGISTRO - Persistencia en base de datos
     */
    public function store(ClienteRequest $request)
    {
        $cliente = Cliente::create($request->validated());

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('success', 'Cliente registrado exitosamente.');
    }

    /**
     * VISTA DE DETALLE
     */
    public function show(Cliente $cliente)
    {
        $cliente->load([
            'coach',
            'membresiaClientes.membresia',
            'membresiaClientes.vendedor',
            'rutinas.ejercicios',
            'ventas.detalles.producto',
        ]);

        $membresiaActiva = $cliente->membresiaActiva();
        $historialMembresias = $cliente->membresiaClientes()
            ->with(['membresia', 'vendedor'])
            ->orderBy('created_at', 'desc')
            ->get();

        $rutinas = $cliente->rutinas()->with('ejercicios')->orderBy('created_at', 'desc')->get();
        $coaches = User::coaches()->activos()->orderBy('name')->get();
        $membresias = Membresia::activas()->orderBy('precio')->get();

        return view('recepcionista.clientes.show', compact(
            'cliente',
            'membresiaActiva',
            'historialMembresias',
            'rutinas',
            'coaches',
            'membresias'
        ));
    }

    /**
     * FORMULARIO DE EDICIÓN
     */
    public function edit(Cliente $cliente)
    {
        $coaches = User::coaches()->activos()->orderBy('name')->get();
        return view('recepcionista.clientes.edit', compact('cliente', 'coaches'));
    }

    /**
     * EDITAR REGISTRO - Persistencia en base de datos
     */
    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('success', 'Datos del cliente actualizados exitosamente.');
    }

    /**
     * ELIMINACIÓN DE REGISTROS
     */
    public function destroy(Cliente $cliente)
    {
        // Soft delete lógico
        $cliente->update(['activo' => false]);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }

    /**
     * VENDER MEMBRESÍA a un cliente
     */
    public function venderMembresia(MembresiaClienteRequest $request, Cliente $cliente)
    {
        // Verificar si ya tiene membresía activa
        if ($cliente->tieneMembresiaActiva()) {
            return back()
                ->withErrors(['membresia_id' => 'El cliente ya tiene una membresía activa. Cancele la actual primero.'])
                ->withInput();
        }

        $membresia = Membresia::findOrFail($request->membresia_id);

        DB::transaction(function () use ($request, $cliente, $membresia) {
            $fechaInicio = now()->toDateString();
            $fechaFin = now()->addDays($membresia->duracion_dias)->toDateString();

            // Crear el registro de membresía del cliente
            MembresiaCliente::create([
                'cliente_id'    => $cliente->id,
                'membresia_id'  => $membresia->id,
                'vendido_por'   => Auth::id(),
                'fecha_inicio'  => $fechaInicio,
                'fecha_fin'     => $fechaFin,
                'precio_pagado' => $membresia->precio,
                'estado'        => 'activa',
            ]);

            // Registrar la venta
            Venta::create([
                'cliente_id'  => $cliente->id,
                'usuario_id'  => Auth::id(),
                'tipo'        => 'membresia',
                'total'       => $membresia->precio,
                'metodo_pago' => $request->metodo_pago,
                'notas'       => 'Membresía ' . $membresia->nombre . ' - ' . $membresia->duracion_dias . ' días',
            ]);
        });

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('success', "Membresía {$membresia->nombre} vendida exitosamente.");
    }

    /**
     * CANCELAR MEMBRESÍA de un cliente
     */
    public function cancelarMembresia(Cliente $cliente)
    {
        $membresiaActiva = $cliente->membresiaActiva();

        if (!$membresiaActiva) {
            return back()->with('error', 'El cliente no tiene una membresía activa para cancelar.');
        }

        $membresiaActiva->update(['estado' => 'cancelada']);

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('success', 'Membresía cancelada exitosamente.');
    }

    /**
     * ASIGNAR O CAMBIAR COACH a un cliente
     */
    public function asignarCoach(AsignarCoachRequest $request, Cliente $cliente)
    {
        $coach = User::coaches()->where('id', $request->coach_id)->first();

        if (!$coach) {
            return back()->withErrors(['coach_id' => 'El usuario seleccionado no es un coach válido.']);
        }

        $accion = $cliente->coach_id ? 'cambiado' : 'asignado';

        $cliente->update(['coach_id' => $request->coach_id]);

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('success', "Coach {$coach->name} {$accion} exitosamente.");
    }

    /**
     * Quitar coach de un cliente
     */
    public function quitarCoach(Cliente $cliente)
    {
        $cliente->update(['coach_id' => null]);

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('success', 'Coach removido del cliente exitosamente.');
    }
}