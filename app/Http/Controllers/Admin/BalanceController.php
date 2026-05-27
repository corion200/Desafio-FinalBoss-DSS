<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{

    /**
     * BALANCE GENERAL - Usando Query Builder y Eloquent
     */
    public function index(Request $request)
    {
        if ($request->filled('desde') || $request->filled('hasta')) {
            $request->validate([
                'desde' => ['required', 'date'],
                'hasta' => ['required', 'date', 'after_or_equal:desde'],
            ], [
                'desde.required' => 'La fecha inicial es obligatoria.',
                'hasta.required' => 'La fecha final es obligatoria.',
                'desde.date'     => 'La fecha inicial debe ser una fecha válida.',
                'hasta.date'     => 'La fecha final debe ser una fecha válida.',
                'hasta.after_or_equal' => 'La fecha final (Hasta) no puede ser anterior a la fecha inicial (Desde).',
            ]);
        }

        $desde = $request->filled('desde') ? $request->desde : now()->startOfMonth()->toDateString();
        $hasta = $request->filled('hasta') ? $request->hasta : now()->toDateString();

        // ============ QUERY BUILDER ============

        // Total ingresos por productos en el período
        $ingresosProductos = DB::table('ventas')
            ->where('tipo', 'producto')
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->sum('total');

        // Total ingresos por membresías en el período
        $ingresosMembresias = DB::table('ventas')
            ->where('tipo', 'membresia')
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->sum('total');

        // Total general
        $totalIngresos = DB::table('ventas')
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->sum('total');

        // Cantidad de ventas por tipo
        $cantidadVentasProductos = DB::table('ventas')
            ->where('tipo', 'producto')
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->count();

        $cantidadVentasMembresias = DB::table('ventas')
            ->where('tipo', 'membresia')
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->count();

        // Ventas por método de pago
        $ventasPorMetodoPago = DB::table('ventas')
            ->select('metodo_pago', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->groupBy('metodo_pago')
            ->get();

        // Productos más vendidos (Query Builder con join)
        $productosMasVendidos = DB::table('venta_detalles')
            ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id')
            ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
            ->select(
                'productos.nombre',
                DB::raw('SUM(venta_detalles.cantidad) as total_vendido'),
                DB::raw('SUM(venta_detalles.subtotal) as ingresos')
            )
            ->whereBetween('ventas.created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->take(10)
            ->get();

        // Productos con stock bajo (Eloquent)
        $productosStockBajo = Producto::activos()
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->get();

        // ============ ELOQUENT ============
        // Ventas diarias del período
        $ventasDiarias = Venta::entreFechas($desde . ' 00:00:00', $hasta . ' 23:59:59')
            ->select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(CASE WHEN tipo = "producto" THEN total ELSE 0 END) as productos_dia'),
                DB::raw('SUM(CASE WHEN tipo = "membresia" THEN total ELSE 0 END) as membresias_dia'),
                DB::raw('SUM(total) as total_dia'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return view('admin.balance.index', compact(
            'desde',
            'hasta',
            'ingresosProductos',
            'ingresosMembresias',
            'totalIngresos',
            'cantidadVentasProductos',
            'cantidadVentasMembresias',
            'ventasPorMetodoPago',
            'productosMasVendidos',
            'productosStockBajo',
            'ventasDiarias'
        ));
    }
}