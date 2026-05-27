<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\MembresiaCliente;
use App\Models\ReporteEquipo;
use App\Models\Rutina;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin'        => $this->adminDashboard(),
            'recepcionista'=> $this->recepcionistaDashboard(),
            'coach'        => $this->coachDashboard(),
            default        => abort(403),
        };
    }

    private function adminDashboard()
    {
        // Usando Query Builder para el balance
        $totalVentasProductos = DB::table('ventas')
            ->where('tipo', 'producto')
            ->sum('total');

        $totalVentasMembresias = DB::table('ventas')
            ->where('tipo', 'membresia')
            ->sum('total');

        $totalIngresos = DB::table('ventas')->sum('total');

        $ventasHoy = Venta::deHoy()->count();
        $ventasMes = Venta::delMes()->count();

        $clientesActivos = Cliente::activos()->count();
        $clientesConMembresia = Cliente::conMembresiaActiva()->count();

        $productosSinStock = Producto::sinStock()->activos()->count();
        $reportesPendientes = ReporteEquipo::pendientes()->count();

        // Últimas ventas usando Eloquent
        $ultimasVentas = Venta::with(['cliente', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Ventas del mes agrupadas por día (Query Builder)
        $ventasPorDia = DB::table('ventas')
            ->select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(total) as total_dia'),
                DB::raw('COUNT(*) as cantidad_ventas')
            )
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return view('admin.dashboard', compact(
            'totalVentasProductos',
            'totalVentasMembresias',
            'totalIngresos',
            'ventasHoy',
            'ventasMes',
            'clientesActivos',
            'clientesConMembresia',
            'productosSinStock',
            'reportesPendientes',
            'ultimasVentas',
            'ventasPorDia'
        ));
    }

    private function recepcionistaDashboard()
    {
        $clientesActivos = Cliente::activos()->count();
        $clientesConMembresia = Cliente::conMembresiaActiva()->count();
        $membresiasVendidasHoy = MembresiaCliente::activas()
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $ventasHoy = Venta::deHoy()->deProductos()->count();
        $productosSinStock = Producto::sinStock()->activos()->count();

        $ultimosClientes = Cliente::with('coach')
            ->activos()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('recepcionista.dashboard', compact(
            'clientesActivos',
            'clientesConMembresia',
            'membresiasVendidasHoy',
            'ventasHoy',
            'productosSinStock',
            'ultimosClientes'
        ));
    }

    private function coachDashboard()
    {
        $user = Auth::user();

        // Usando Eloquent con relaciones
        $misClientes = $user->clientes()->activos()->count();
        $misRutinas = $user->rutinas()->activas()->count();
        $misReportes = $user->reportesEquipo()->pendientes()->count();

        $clientesAsignados = $user->clientes()
            ->activos()
            ->with(['membresiaClientes' => function ($q) {
                $q->where('estado', 'activa')
                  ->where('fecha_fin', '>=', now()->toDateString());
            }])
            ->orderBy('apellido')
            ->take(10)
            ->get();

        return view('coach.dashboard', compact(
            'misClientes',
            'misRutinas',
            'misReportes',
            'clientesAsignados'
        ));
    }
}