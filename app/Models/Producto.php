<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'activo' => 'boolean',
    ];

    // ==================== RELACIONES ====================

    // Un producto tiene muchos detalles de venta
    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    // BELONGS TO MANY: Un producto aparece en muchas ventas
    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'venta_detalles')
                    ->withPivot(['cantidad', 'precio_unitario', 'subtotal'])
                    ->withTimestamps();
    }

    // ==================== SCOPES ====================

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeSinStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    // ==================== MÉTODOS ====================

    public function tieneStock(int $cantidad = 1): bool
    {
        return $this->stock >= $cantidad;
    }

    // Query Builder: reducir stock
    public function reducirStock(int $cantidad): void
    {
        $this->decrement('stock', $cantidad);
    }

    // Query Builder: aumentar stock
    public function aumentarStock(int $cantidad): void
    {
        $this->increment('stock', $cantidad);
    }
}