<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'primerApellido',
        'segundoApellido',
        'fechaNacimiento',
        'genero',
        'numeroRFID',
        'email',
        'password',
        'role',
        'puntos',
        'estado'
    ];

    protected $casts = [
        'puntos' => 'integer',
        'fechaNacimiento' => 'date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relación con los registros de puntos - CORREGIDO
     */
    public function registrosPuntos()
    {
        return $this->hasMany(RegistroPuntos::class, 'usuario_id');
    }

    /**
     * Relación con canjes
     */
    public function registros()
    {
        return $this->hasMany(RegistroCanjeo::class);
    }

    /**
     * Decrementar puntos de forma segura
     */
    public function decrementarPuntos($puntos)
    {
        if ($this->puntos < $puntos) {
            throw new \Exception('No tiene suficientes puntos');
        }

        $this->decrement('puntos', $puntos);
        return $this;
    }

    /**
     * Incrementar puntos
     */
    public function incrementarPuntos($puntos)
    {
        $this->increment('puntos', $puntos);
        return $this;
    }

    /**
     * Verificar si puede canjear un producto
     */
    public function puedeCanjear($puntosNecesarios)
    {
        return $this->puntos >= $puntosNecesarios;
    }

    /**
     * Obtener puntos disponibles formateados
     */
    public function getPuntosFormateadosAttribute()
    {
        return number_format($this->puntos, 0);
    }

    /**
     * Verificar si es estudiante
     */
    public function esEstudiante()
    {
        return $this->role === 'estudiante';
    }

    /**
     * Verificar si es administrador
     */
    public function esAdministrador()
    {
        return $this->role === 'administrador';
    }

    /**
     * Verificar si está activo
     */
    public function estaActivo()
    {
        return $this->estado === 'activo';
    }

    /**
     * Obtener canjes del usuario
     */
    public function canjes()
    {
        return $this->registros()->with('producto');
    }

    /**
     * Obtener total de puntos canjeados
     */
    public function totalPuntosCanjeados()
    {
        return $this->registros()->sum('puntos_totales');
    }

    // Métodos helper
    public function nombreCompleto()
    {
        return $this->nombre . ' ' . $this->primerApellido .
               ($this->segundoApellido ? ' ' . $this->segundoApellido : '');
    }

    public function addPuntos($cantidad)
    {
        $this->puntos += $cantidad;
        $this->save();
    }

    public function restarPuntos($cantidad)
    {
        $this->puntos -= $cantidad;
        $this->save();
    }

    /**
     * Scope para estudiantes activos
     */
    public function scopeEstudiantesActivos($query)
    {
        return $query->where('role', 'estudiante')
                    ->where('estado', 'activo');
    }

    /**
     * Scope por RFID
     */
    public function scopePorRfid($query, $rfid)
    {
        return $query->where('numeroRFID', $rfid);
    }
    public function registroPuntos()
{
    return $this->hasMany(RegistroPuntos::class);
}

}
