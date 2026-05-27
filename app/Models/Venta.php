<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'tipo',
        'total',
        'metodo_pago',
        'notas',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // ==================== RELACIONES ====================

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    // BELONGS TO MANY: Una venta tiene muchos productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'venta_detalles')
                    ->withPivot(['cantidad', 'precio_unitario', 'subtotal'])
                    ->withTimestamps();
    }

    // ==================== SCOPES ====================

    public function scopeDeProductos($query)
    {
        return $query->where('tipo', 'producto');
    }

    public function scopeDeMembresias($query)
    {
        return $query->where('tipo', 'membresia');
    }

    public function scopeDeHoy($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    public function scopeDelMes($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('created_at', [$desde, $hasta]);
    }
}