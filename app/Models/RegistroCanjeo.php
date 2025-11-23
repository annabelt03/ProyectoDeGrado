<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroCanjeo extends Model
{
    use HasFactory;
    protected $table = 'registros';
    protected $fillable = [
        'usuario_id',
        'producto_id',
        'cantidad',
        'puntos_totales',
        'estado',
        'fecha_canjeo'
    ];

    protected $casts = [
        'fecha_canjeo' => 'datetime',
        'puntos_totales' => 'integer',
        'cantidad' => 'integer'
    ];

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Relación con Producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Scope para canjes por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para canjes recientes
     */
    public function scopeRecientes($query, $dias = 7)
    {
        return $query->where('fecha_canjeo', '>=', now()->subDays($dias));
    }

    /**
     * Accessor para el total formateado
     */
    public function getPuntosTotalesFormateadosAttribute()
    {
        return number_format($this->puntos_totales, 0);
    }
}