<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membresia;
use Illuminate\Http\Request;

class MembresiaController extends Controller
{

    public function index()
    {
        $membresias = Membresia::orderBy('precio')->get();
        return view('admin.membresias.index', compact('membresias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:100',
            'duracion_dias' => 'required|integer|min:1',
            'precio'        => 'required|numeric|min:0',
            'descripcion'   => 'nullable|string|max:500',
        ]);

        Membresia::create($request->all());

        return redirect()->route('admin.membresias.index')
            ->with('success', 'Membresía creada exitosamente.');
    }

    public function update(Request $request, Membresia $membresia)
    {
        $request->validate([
            'nombre'        => 'required|string|max:100',
            'duracion_dias' => 'required|integer|min:1',
            'precio'        => 'required|numeric|min:0',
            'descripcion'   => 'nullable|string|max:500',
            'activa'        => 'boolean',
        ]);

        $membresia->update($request->all());

        return redirect()->route('admin.membresias.index')
            ->with('success', 'Membresía actualizada exitosamente.');
    }
}