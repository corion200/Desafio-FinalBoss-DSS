<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembresiaCliente extends Model
{
    use HasFactory;

    protected $table = 'membresia_clientes';

    protected $fillable = [
        'cliente_id',
        'membresia_id',
        'vendido_por',
        'fecha_inicio',
        'fecha_fin',
        'precio_pagado',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'precio_pagado' => 'decimal:2',
    ];

    // ==================== RELACIONES ====================

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendido_por');
    }

    // ==================== SCOPES ====================

    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    public function scopeExpiradas($query)
    {
        return $query->where('estado', 'activa')
                     ->where('fecha_fin', '<', now()->toDateString());
    }
}