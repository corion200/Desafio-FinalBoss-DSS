<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'rutina_id',
        'nombre',
        'grupo_muscular',
        'series',
        'repeticiones',
        'descanso_segundos',
        'dia_semana',
        'notas',
    ];

    protected $casts = [
        'series' => 'integer',
        'repeticiones' => 'integer',
        'descanso_segundos' => 'integer',
    ];

    // ==================== RELACIONES ====================

    // BELONGS TO: Un ejercicio pertenece a una rutina
    public function rutina()
    {
        return $this->belongsTo(Rutina::class);
    }
}