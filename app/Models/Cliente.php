<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'email',
        'telefono',
        'fecha_nacimiento',
        'direccion',
        'coach_id',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    // ==================== RELACIONES ====================

    // Un cliente pertenece a un coach (User)
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // Un cliente tiene muchas membresías (historial)
    public function membresiaClientes()
    {
        return $this->hasMany(MembresiaCliente::class);
    }

    // Un cliente tiene muchas ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    // Un cliente tiene muchas rutinas
    public function rutinas()
    {
        return $this->hasMany(Rutina::class);
    }

    // RELACIÓN BELONGS TO MANY: Un cliente puede tener varias membresías a través de la tabla intermedia
    public function membresias()
    {
        return $this->belongsToMany(Membresia::class, 'membresia_clientes')
                    ->withPivot(['id', 'vendido_por', 'fecha_inicio', 'fecha_fin', 'precio_pagado', 'estado'])
                    ->withTimestamps();
    }

    // ==================== SCOPES ====================

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeConMembresiaActiva($query)
    {
        return $query->whereHas('membresiaClientes', function ($q) {
            $q->where('estado', 'activa')->where('fecha_fin', '>=', now()->toDateString());
        });
    }

    public function scopeSinCoach($query)
    {
        return $query->whereNull('coach_id');
    }

    public function scopeConCoach($query)
    {
        return $query->whereNotNull('coach_id');
    }

    // ==================== MÉTODOS ====================

    public function tieneMembresiaActiva(): bool
    {
        return $this->membresiaClientes()
                    ->where('estado', 'activa')
                    ->where('fecha_fin', '>=', now()->toDateString())
                    ->exists();
    }

    public function membresiaActiva()
    {
        return $this->membresiaClientes()
                    ->where('estado', 'activa')
                    ->where('fecha_fin', '>=', now()->toDateString())
                    ->first();
    }

    public function nombreCompleto(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}