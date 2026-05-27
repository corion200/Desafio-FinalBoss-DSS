<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\ProductoRequest;
use App\Http\Requests\ReponerStockRequest;
use Illuminate\Http\Request;

class ProductoController extends Controller
{

    /**
     * LISTADO DE REGISTROS
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('buscar')) {
            $buscar = $request->get('buscar');
            $query->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
        }

        if ($request->filled('categoria')) {
            $query->porCategoria($request->get('categoria'));
        }

        if ($request->filled('stock_status')) {
            if ($request->get('stock_status') === 'sin_stock') {
                $query->sinStock();
            } elseif ($request->get('stock_status') === 'con_stock') {
                $query->conStock();
            }
        }

        $productos = $query->orderBy('categoria')->orderBy('nombre')->paginate(15);

        return view('admin.productos.index', compact('productos'));
    }

    /**
     * FORMULARIO DE CREACIÓN
     */
    public function create()
    {
        return view('admin.productos.create');
    }

    /**
     * CREAR REGISTRO
     */
    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * VISTA DE DETALLE
     */
    public function show(Producto $producto)
    {
        // Usando Query Builder para estadísticas de ventas
        $totalVendido = \DB::table('venta_detalles')
            ->where('producto_id', $producto->id)
            ->sum('cantidad');

        $ingresosGenerados = \DB::table('venta_detalles')
            ->where('producto_id', $producto->id)
            ->sum('subtotal');

        $ultimasVentas = \DB::table('venta_detalles')
            ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
            ->join('users', 'ventas.usuario_id', '=', 'users.id')
            ->leftJoin('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->select(
                'venta_detalles.cantidad',
                'venta_detalles.subtotal',
                'ventas.created_at',
                'users.name as vendedor',
                'clientes.nombre as cliente_nombre',
                'clientes.apellido as cliente_apellido'
            )
            ->where('venta_detalles.producto_id', $producto->id)
            ->orderBy('ventas.created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.productos.show', compact('producto', 'totalVendido', 'ingresosGenerados', 'ultimasVentas'));
    }

    /**
     * FORMULARIO DE EDICIÓN
     */
    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', compact('producto'));
    }

    /**
     * EDITAR REGISTRO
     */
    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()
            ->route('admin.productos.show', $producto)
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * ELIMINACIÓN DE REGISTROS
     */
    public function destroy(Producto $producto)
    {
        $producto->update(['activo' => false]);

        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    /**
     * REPONER STOCK (funcionalidad específica del admin)
     */
    public function reponerStock(ReponerStockRequest $request, Producto $producto)
    {
        $producto->aumentarStock($request->cantidad);

        return redirect()
            ->route('admin.productos.show', $producto)
            ->with('success', "Se agregaron {$request->cantidad} unidades al stock. Stock actual: {$producto->stock}");
    }
}