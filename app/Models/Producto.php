<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombreProducto',
        'descripcion',
        'puntos_valor',
        'stock',
        'imagen',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'puntos_valor' => 'integer',
        'stock' => 'integer'
    ];

    /**
     * Scope para productos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para productos con stock disponible
     */
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope para productos con bajo stock
     */
    public function scopeBajoStock($query, $umbral = 5)
    {
        return $query->where('stock', '<=', $umbral);
    }

    /**
     * Getter para la URL de la imagen
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return Storage::disk('public')->url($this->imagen);
        }
        return null;
    }
}