<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    protected $table = 'deuda';
    protected $primaryKey = 'id_deuda';
    public $timestamps = false;

    protected $fillable = [
        'valor_deuda',
        'fecha_actualizacion',
        'meses_prolongacion',
        'cantidad_mensual',
        'id_mes',
        'id_usuario'
    ];

    protected $casts = [
        'valor_deuda' => 'decimal:2',
        'cantidad_mensual' => 'decimal:2',
        'fecha_actualizacion' => 'date',
        'meses_prolongacion' => 'integer',
        'id_mes' => 'integer',
        'id_usuario' => 'integer'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'id_mes', 'id_mes');
    }

    // Scopes
    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopePorMes($query, $idMes)
    {
        return $query->where('id_mes', $idMes);
    }

    public function scopeActivas($query)
    {
        return $query->where('valor_deuda', '>', 0);
    }

    // Métodos útiles
    public function calcularCantidadMensual()
    {
        if ($this->meses_prolongacion > 0) {
            $this->cantidad_mensual = $this->valor_deuda / $this->meses_prolongacion;
            return $this->cantidad_mensual;
        }
        return 0;
    }

    public static function totalPorUsuario($idUsuario)
    {
        return self::where('id_usuario', $idUsuario)
                   ->activas()
                   ->sum('valor_deuda');
    }

    // Accessor para verificar si está completamente pagada
    public function getEstaPagadaAttribute()
    {
        return $this->valor_deuda <= 0;
    }
}