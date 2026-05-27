<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteEquipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'equipo_nombre',
        'ubicacion',
        'descripcion',
        'estado',
        'prioridad',
        'fecha_resolucion',
        'resolucion',
    ];

    protected $casts = [
        'fecha_resolucion' => 'date',
    ];

    // ==================== RELACIONES ====================

    // BELONGS TO: Un reporte pertenece a un coach (User)
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // ==================== SCOPES ====================

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnReparacion($query)
    {
        return $query->where('estado', 'en_reparacion');
    }

    public function scopeResueltos($query)
    {
        return $query->where('estado', 'resuelto');
    }

    public function scopeAltaPrioridad($query)
    {
        return $query->where('prioridad', 'alta');
    }
}