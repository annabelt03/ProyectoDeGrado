<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroPuntos extends Model
{
    use HasFactory;

    protected $table = 'registro_puntos';

    protected $fillable = [
        'msg_id',
        'usuario_id',
        'numeroRFID',
        'peso_gramos',
        'puntos_asignados',
        'leido_en'
    ];

    protected $casts = [
        'leido_en' => 'datetime',
        'puntos_asignados' => 'decimal:2',
        'peso_gramos' => 'integer'
    ];

    // Relación con usuario (opcional)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

}
