<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaGasto extends Model
{
    protected $table = 'categoria_gasto';
    protected $primaryKey = 'id_categoria_gasto';
    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria_gasto',
        'id_usuario'
    ];

    protected $casts = [
        'id_usuario' => 'integer'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function egresos()
    {
        // CORRECCIÓN: La relación apunta a Egreso, no a "gastos"
        return $this->hasMany(Egreso::class, 'id_categoria_gasto', 'id_categoria_gasto');
    }

    // Alias para mayor claridad
    public function gastos()
    {
        return $this->egresos();
    }

    // Scopes
    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopeBuscarPorNombre($query, $nombre)
    {
        return $query->where('nombre_categoria_gasto', 'LIKE', "%{$nombre}%");
    }

    // Métodos útiles
    public static function obtenerOCrear($nombre, $idUsuario)
    {
        return self::firstOrCreate(
            [
                'nombre_categoria_gasto' => $nombre,
                'id_usuario' => $idUsuario
            ]
        );
    }

    // CORRECCIÓN: camelCase y campo correcto
    public function totalGastos($idMes = null)
    {
        $query = $this->egresos();
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        // CORRECCIÓN: El campo es valor_egreso, no valor_gasto
        return $query->sum('valor_egreso');
    }

    // Método adicional: contar cantidad de gastos
    public function cantidadGastos($idMes = null)
    {
        $query = $this->egresos();
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->count();
    }

    // Método adicional: promedio de gastos
    public function promedioGastos($idMes = null)
    {
        $query = $this->egresos();
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->avg('valor_egreso');
    }

    // Accessor para nombre formateado
    public function getNombreFormateadoAttribute()
    {
        return ucwords(strtolower($this->nombre_categoria_gasto));
    }

    // Accessor para verificar si tiene gastos
    public function getTieneGastosAttribute()
    {
        return $this->egresos()->exists();
    }
}