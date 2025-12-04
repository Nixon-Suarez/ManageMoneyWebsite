<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaIngreso extends Model
{
    protected $table = 'categoria_ingreso';
    protected $primaryKey = 'id_categoria_ingreso';
    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria_ingreso',
        'id_usuario',
        'estado'
    ];

    protected $casts = [
        'id_usuario' => 'integer',
        'estado' => 'string'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class, 'id_categoria_ingreso', 'id_categoria_ingreso');
    }

    // Scopes
    public function scopePorUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    public function scopeBuscarPorNombre($query, $nombre)
    {
        return $query->where('nombre_categoria_ingreso', 'LIKE', "%{$nombre}%");
    }

    // Métodos útiles
    public static function obtenerOCrear($nombre, $idUsuario)
    {
        return self::firstOrCreate(
            [
                'nombre_categoria_ingreso' => $nombre,
                'id_usuario' => $idUsuario
            ]
        );
    }

    // Calcular total de ingresos por categoría
    public function totalIngresos($idMes = null)
    {
        $query = $this->ingresos();
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->sum('valor_ingreso');
    }

    // Contar cantidad de ingresos
    public function cantidadIngresos($idMes = null)
    {
        $query = $this->ingresos();
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->count();
    }

    // Promedio de ingresos
    public function promedioIngresos($idMes = null)
    {
        $query = $this->ingresos();
        
        if ($idMes) {
            $query->where('id_mes', $idMes);
        }
        
        return $query->avg('valor_ingreso');
    }

    // Accessor para nombre formateado
    public function getNombreFormateadoAttribute()
    {
        return ucwords(strtolower($this->nombre_categoria_ingreso));
    }

    // Accessor para verificar si tiene ingresos
    public function getTieneIngresosAttribute()
    {
        return $this->ingresos()->exists();
    }
}