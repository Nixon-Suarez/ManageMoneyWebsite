<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre_usuario',
        'email',
        'tipo',
        'clave',
        'usuario_usuario'
    ];

    protected $casts = [
        'tipo' => 'integer'
    ];

    // Relaciones
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class, 'id_usuario', 'id_usuario');
    }

    public function egresos()
    {
        return $this->hasMany(Egreso::class, 'id_usuario', 'id_usuario');
    }

    public function deudas()
    {
        return $this->hasMany(Deuda::class, 'id_usuario', 'id_usuario');
    }

    // Scopes útiles
    public function scopeAdministradores($query)
    {
        return $query->where('tipo', 1);
    }

    public function scopeUsuariosRegulares($query)
    {
        return $query->where('tipo', 0);
    }

    // Accessor para verificar si es admin
    public function getEsAdminAttribute()
    {
        return $this->tipo === 1;
    }
}