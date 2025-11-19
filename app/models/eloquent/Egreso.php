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
        'id_categoria_gasto',
        'egresoAdjunto'
    ];

    protected $casts = [
        'valor_egreso' => 'decimal:2',
        'fecha_actualizacion' => 'date',
        'id_mes' => 'integer',
        'id_usuario' => 'integer',
        'id_categoria_gasto' => 'integer',
        'egresoAdjunto' => 'string'
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
        return $this->belongsTo(CategoriaGasto::class, 'id_categoria_gasto', 'id_categoria_gasto');
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
        return $query->where('id_categoria_gasto', $idCategoria);
    }

    public function scopeDelMesActual($query)
    {
        return $query->whereMonth('fecha_actualizacion', date('m'))
                     ->whereYear('fecha_actualizacion', date('Y'));
    }

    public function scopeSinCategoria($query)
    {
        return $query->whereNull('id_categoria_gasto');
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
        $query = self::where('id_categoria_gasto', $idCategoria);
        
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
                     ->groupBy('id_categoria_gasto')
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