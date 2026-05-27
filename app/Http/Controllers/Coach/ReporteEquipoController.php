<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ReporteEquipo;
use App\Http\Requests\ReporteEquipoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteEquipoController extends Controller
{

    /**
     * FORMULARIO PARA REPORTAR EQUIPO DAÑADO
     */
    public function create()
    {
        return view('coach.reportes.create');
    }

    /**
     * GUARDAR REPORTE
     */
    public function store(ReporteEquipoRequest $request)
    {
        ReporteEquipo::create([
            'coach_id'     => Auth::id(),
            'equipo_nombre'=> $request->equipo_nombre,
            'ubicacion'    => $request->ubicacion,
            'descripcion'  => $request->descripcion,
            'prioridad'    => $request->prioridad,
            'estado'       => 'pendiente',
        ]);

        return redirect()
            ->route('coach.reportes.create')
            ->with('success', 'Reporte de equipo dañado enviado exitosamente.');
    }

    /**
     * VER MIS REPORTES
     */
    public function index()
    {
        $reportes = ReporteEquipo::where('coach_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('coach.reportes.index', compact('reportes'));
    }
}