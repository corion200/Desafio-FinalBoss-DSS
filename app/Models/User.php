<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    // ==================== RELACIONES ====================

    // Un coach tiene muchos clientes asignados
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'coach_id');
    }

    // Un usuario (recepcionista/admin) puede vender muchas membresías
    public function membresiasVendidas()
    {
        return $this->hasMany(MembresiaCliente::class, 'vendido_por');
    }

    // Un usuario puede hacer muchas ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'usuario_id');
    }

    // Un coach puede crear muchas rutinas
    public function rutinas()
    {
        return $this->hasMany(Rutina::class, 'coach_id');
    }

    // Un coach puede hacer muchos reportes de equipo
    public function reportesEquipo()
    {
        return $this->hasMany(ReporteEquipo::class, 'coach_id');
    }

    // ==================== SCOPES Y MÉTODOS ====================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRecepcionista(): bool
    {
        return $this->role === 'recepcionista';
    }

    public function isCoach(): bool
    {
        return $this->role === 'coach';
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeRecepcionistas($query)
    {
        return $query->where('role', 'recepcionista');
    }

    public function scopeCoaches($query)
    {
        return $query->where('role', 'coach');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}