<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\Cliente;
use App\Http\Requests\VentaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{

    /**
     * FORMULARIO DE CREACIÓN DE VENTA DE PRODUCTOS
     */
    public function create()
    {
        $productos = Producto::activos()->conStock()->orderBy('nombre')->get();
        $clientes = Cliente::activos()->orderBy('apellido')->get();

        return view('recepcionista.ventas.create', compact('productos', 'clientes'));
    }

    /**
     * PROCESAR VENTA DE PRODUCTOS
     */
    public function store(VentaRequest $request)
    {
        // Validar stock disponible
        foreach ($request->productos as $item) {
            $producto = Producto::find($item['id']);
            if (!$producto || !$producto->tieneStock($item['cantidad'])) {
                return back()
                    ->withErrors(["producto_{$item['id']}" => "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}"])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($request) {
            $total = 0;
            $detalles = [];

            foreach ($request->productos as $item) {
                $producto = Producto::find($item['id']);
                $subtotal = $producto->precio * $item['cantidad'];
                $total += $subtotal;

                $detalles[] = [
                    'producto_id'     => $producto->id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal'        => $subtotal,
                ];

                // Reducir stock
                $producto->reducirStock($item['cantidad']);
            }

            // Crear la venta
            $venta = Venta::create([
                'cliente_id'  => $request->cliente_id,
                'usuario_id'  => Auth::id(),
                'tipo'        => 'producto',
                'total'       => $total,
                'metodo_pago' => $request->metodo_pago,
                'notas'       => $request->notas,
            ]);

            // Crear los detalles
            foreach ($detalles as $detalle) {
                $detalle['venta_id'] = $venta->id;
                VentaDetalle::create($detalle);
            }
        });

        return redirect()
            ->route('ventas.create')
            ->with('success', 'Venta registrada exitosamente.');
    }

    /**
     * HISTORIAL DE VENTAS
     */
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario', 'detalles.producto'])
            ->deProductos();

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->entreFechas($request->desde . ' 00:00:00', $request->hasta . ' 23:59:59');
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        $ventas = $query->orderBy('created_at', 'desc')->paginate(15);

        // Total del período usando Query Builder
        $totalPeriodo = DB::table('ventas')
            ->where('tipo', 'producto');

        if ($request->filled('desde') && $request->filled('hasta')) {
            $totalPeriodo->whereBetween('created_at', [
                $request->desde . ' 00:00:00',
                $request->hasta . ' 23:59:59'
            ]);
        }
        $totalPeriodo = $totalPeriodo->sum('total');

        return view('recepcionista.ventas.index', compact('ventas', 'totalPeriodo'));
    }
    /**
     * VER DETALLE DE VENTA
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'usuario', 'detalles.producto']);
        return view('recepcionista.ventas.show', compact('venta'));
    }
}