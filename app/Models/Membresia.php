<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'duracion_dias',
        'precio',
        'descripcion',
        'activa',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activa' => 'boolean',
    ];

    // ==================== RELACIONES ====================

    // Una membresía tiene muchos registros en membresia_clientes
    public function membresiaClientes()
    {
        return $this->hasMany(MembresiaCliente::class);
    }

    // BELONGS TO MANY: Una membresía pertenece a muchos clientes
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'membresia_clientes')
                    ->withPivot(['id', 'vendido_por', 'fecha_inicio', 'fecha_fin', 'precio_pagado', 'estado'])
                    ->withTimestamps();
    }

    // ==================== SCOPES ====================

    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}