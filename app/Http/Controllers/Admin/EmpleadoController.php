<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    /**
     * LISTADO DE EMPLEADOS
     */
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->get('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'LIKE', "%{$buscar}%")
                  ->orWhere('email', 'LIKE', "%{$buscar}%")
                  ->orWhere('telefono', 'LIKE', "%{$buscar}%");
            });
        }

        // Filtro por Rol
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Filtro por Estado
        if ($request->filled('estado')) {
            $query->where('activo', $request->get('estado') === 'activo');
        }

        $empleados = $query->orderBy('name')->paginate(15);

        return view('admin.empleados.index', compact('empleados'));
    }

    /**
     * FORMULARIO DE CREACIÓN
     */
    public function create()
    {
        return view('admin.empleados.create');
    }

    /**
     * REGISTRAR EMPLEADO
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', 'in:recepcionista,coach'],
            'telefono' => ['nullable', 'string'],
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El formato del correo es inválido.',
            'email.unique'      => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required'     => 'El puesto o rol es obligatorio.',
            'role.in'           => 'El puesto seleccionado no es válido.',
        ]);

        $telefono = null;
        if ($request->filled('telefono')) {
            if (!preg_match('/^[0-9\s\-]+$/', $request->telefono)) {
                return back()->withErrors(['telefono' => 'El teléfono solo debe contener números, espacios o guiones.'])->withInput();
            }

            $digits = preg_replace('/[^0-9]/', '', $request->telefono);
            if (strlen($digits) !== 8) {
                return back()->withErrors(['telefono' => 'El teléfono debe contener exactamente 8 números (ej. 1234-5678).'])->withInput();
            }

            $telefono = substr($digits, 0, 4) . '-' . substr($digits, 4, 4);
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'telefono' => $telefono,
            'activo'   => true,
        ]);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * VISTA DE DETALLE
     */
    public function show(User $empleado)
    {
        if ($empleado->role === 'admin') {
            abort(403, 'No se puede visualizar información de administradores aquí.');
        }

        $empleado->loadCount(['clientes', 'ventas', 'reportesEquipo']);

        return view('admin.empleados.show', compact('empleado'));
    }

    /**
     * FORMULARIO DE EDICIÓN
     */
    public function edit(User $empleado)
    {
        if ($empleado->role === 'admin') {
            abort(403, 'No se permite editar administradores.');
        }

        return view('admin.empleados.edit', compact('empleado'));
    }

    /**
     * ACTUALIZAR EMPLEADO
     */
    public function update(Request $request, User $empleado)
    {
        if ($empleado->role === 'admin') {
            abort(403, 'No se permite editar administradores.');
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignore($empleado->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', 'in:recepcionista,coach'],
            'telefono' => ['nullable', 'string'],
            'activo'   => ['required', 'boolean'],
        ], [
            'name.required'  => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'El formato del correo es inválido.',
            'email.unique'   => 'Este correo electrónico ya está registrado.',
            'password.min'   => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required'  => 'El puesto o rol es obligatorio.',
            'role.in'        => 'El puesto seleccionado no es válido.',
        ]);

        $telefono = null;
        if ($request->filled('telefono')) {
            if (!preg_match('/^[0-9\s\-]+$/', $request->telefono)) {
                return back()->withErrors(['telefono' => 'El teléfono solo debe contener números, espacios o guiones.'])->withInput();
            }

            $digits = preg_replace('/[^0-9]/', '', $request->telefono);
            if (strlen($digits) !== 8) {
                return back()->withErrors(['telefono' => 'El teléfono debe contener exactamente 8 números (ej. 1234-5678).'])->withInput();
            }

            $telefono = substr($digits, 0, 4) . '-' . substr($digits, 4, 4);
        }

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'telefono' => $telefono,
            'activo'   => $request->activo,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $empleado->update($data);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Datos del empleado actualizados exitosamente.');
    }

    /**
     * ELIMINAR / DESACTIVAR EMPLEADO
     */
    public function destroy(User $empleado)
    {
        if ($empleado->role === 'admin') {
            abort(403, 'No se permite eliminar administradores.');
        }

        // Desactivación lógica para preservar el historial de ventas y asignaciones
        $empleado->update(['activo' => false]);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'El empleado ha sido desactivado del sistema.');
    }
}
