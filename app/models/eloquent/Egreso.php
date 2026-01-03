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
        'id_usuario',
        'categoria_eg',
        'egresoAdjunto',
        'EgresoEstado',
        'año'
    ];

    protected $casts = [
        'valor_egreso' => 'decimal:2',
        'fecha_actualizacion' => 'date',
        'id_mes' => 'integer',
        'id_usuario' => 'integer',
        'categoria_eg' => 'integer',
        'egresoAdjunto' => 'string',
        'EgresoEstado' => 'string',
        'año' => 'string',
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

    // NUEVA RELACIÓN
    public function categoriaGasto()
    {
        return $this->belongsTo(CategoriaGasto::class, 'categoria_eg', 'categoria_eg');
    }

    // Alias para mayor claridad
    public function categoria()
    {
        return $this->categoriaGasto();
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

    public function scopePorCategoria($query, $idCategoria)
    {
        return $query->where('categoria_eg', $idCategoria);
    }

    public function scopeDelMesActual($query)
    {
        return $query->whereMonth('fecha_actualizacion', date('m'))
                     ->whereYear('fecha_actualizacion', date('Y'));
    }

    public function scopeSinCategoria($query)
    {
        return $query->whereNull('categoria_eg');
    }

    // Métodos útiles
    public static function totalPorUsuario($idUsuario, $idMes = null)
    {
        $query = self::where('id_usuario', $idUsuario);
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->sum('valor_egreso');
    }

    public static function totalPorCategoria($idCategoria, $idMes = null)
    {
        $query = self::where('categoria_eg', $idCategoria);
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->sum('valor_egreso');
    }

    public static function resumenPorCategorias($idUsuario, $idMes = null)
    {
        $query = self::with('categoriaGasto')
                     ->where('id_usuario', $idUsuario);
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->get()
                     ->groupBy('categoria_eg')
                     ->map(function ($egresos) {
                         return [
                             'categoria' => $egresos->first()->categoriaGasto->nombre_categoria_gasto ?? 'Sin categoría',
                             'total' => $egresos->sum('valor_egreso'),
                             'cantidad' => $egresos->count(),
                             'promedio' => $egresos->avg('valor_egreso')
                         ];
                     });
    }
}