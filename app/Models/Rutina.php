<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rutina extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'coach_id',
        'nombre',
        'objetivo',
        'observaciones',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    // ==================== RELACIONES ====================

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // HAS MANY: Una rutina tiene muchos ejercicios
    public function ejercicios()
    {
        return $this->hasMany(Ejercicio::class);
    }

    // ==================== SCOPES ====================

    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    // ==================== MÉTODOS ====================

    // Query Builder: ejercicios agrupados por día
    public function ejerciciosPorDia()
    {
        return $this->ejercicios()
                    ->orderBy('dia_semana')
                    ->orderBy('id')
                    ->get()
                    ->groupBy('dia_semana');
    }
}