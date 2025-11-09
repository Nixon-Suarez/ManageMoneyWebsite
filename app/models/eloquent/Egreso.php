<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    protected $table = 'egreso';
    protected $primaryKey = 'id_egreso';
    public $timestamps = false;

    protected $fillable = [
        'valor_egreso',
        'descripcion',
        'fecha_actualizacion',
        'id_mes',
        'id_usuario'
    ];

    protected $casts = [
        'valor_egreso' => 'decimal:2',
        'fecha_actualizacion' => 'date',
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

    public function scopeDelMesActual($query)
    {
        return $query->whereMonth('fecha_actualizacion', date('m'))
                     ->whereYear('fecha_actualizacion', date('Y'));
    }

    // Método para calcular total de egresos
    public static function totalPorUsuario($idUsuario, $idMes = null)
    {
        $query = self::where('id_usuario', $idUsuario);
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->sum('valor_egreso');
    }
}