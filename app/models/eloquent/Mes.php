<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    protected $table = 'mes';
    protected $primaryKey = 'id_mes';
    public $timestamps = false;

    protected $fillable = [
        'nombre_mes'
    ];

    // Relaciones
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class, 'id_mes', 'id_mes');
    }

    public function egresos()
    {
        return $this->hasMany(Egreso::class, 'id_mes', 'id_mes');
    }

    public function deudas()
    {
        return $this->hasMany(Deuda::class, 'id_mes', 'id_mes');
    }

    // Scope para buscar por nombre
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre_mes', strtoupper($nombre));
    }

    // Método estático para obtener mes actual
    public static function mesActual()
    {
        $mesActual = strtoupper(date('F')); // Nombre del mes en inglés
        $meses = [
            'JANUARY' => 'ENERO',
            'FEBRUARY' => 'FEBRERO',
            'MARCH' => 'MARZO',
            'APRIL' => 'ABRIL',
            'MAY' => 'MAYO',
            'JUNE' => 'JUNIO',
            'JULY' => 'JULIO',
            'AUGUST' => 'AGOSTO',
            'SEPTEMBER' => 'SEPTIEMBRE',
            'OCTOBER' => 'OCTUBRE',
            'NOVEMBER' => 'NOVIEMBRE',
            'DECEMBER' => 'DICIEMBRE'
        ];
        
        return self::porNombre($meses[$mesActual])->first();
    }
}