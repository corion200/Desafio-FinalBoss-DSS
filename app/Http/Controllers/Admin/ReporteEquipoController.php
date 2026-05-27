<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReporteEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteEquipoController extends Controller
{

    /**
     * VER TODOS LOS REPORTES DE EQUIPO
     */
    public function index(Request $request)
    {
        $query = ReporteEquipo::with('coach');

        if ($request->filled('estado')) {
            $query->where('estado', $request->get('estado'));
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->get('prioridad'));
        }

        $reportes = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas con Query Builder
        $estadisticas = [
            'pendientes'     => ReporteEquipo::pendientes()->count(),
            'en_reparacion'  => ReporteEquipo::enReparacion()->count(),
            'resueltos'      => ReporteEquipo::resueltos()->count(),
            'alta_prioridad' => ReporteEquipo::altaPrioridad()->pendientes()->count(),
        ];

        return view('admin.reportes.index', compact('reportes', 'estadisticas'));
    }

    /**
     * VER DETALLE DE REPORTE
     */
    public function show(ReporteEquipo $reporteEquipo)
    {
        $reporteEquipo->load('coach');
        return view('admin.reportes.show', compact('reporteEquipo'));
    }

    /**
     * CAMBIAR ESTADO DEL REPORTE
     */
    public function updateEstado(Request $request, ReporteEquipo $reporteEquipo)
    {
        $request->validate([
            'estado'      => ['required', 'in:pendiente,en_reparacion,resuelto'],
            'resolucion'  => ['required_if:estado,resuelto', 'nullable', 'string', 'max:500'],
        ]);

        $data = ['estado' => $request->estado];

        if ($request->estado === 'resuelto') {
            $data['fecha_resolucion'] = now()->toDateString();
            $data['resolucion'] = $request->resolucion;
        }

        $reporteEquipo->update($data);

        return redirect()
            ->route('admin.reportes.show', $reporteEquipo)
            ->with('success', 'Estado del reporte actualizado.');
    }
}