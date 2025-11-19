<?php
namespace App\Models;
use Carbon\Carbon;

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

    public function scopePendientes($query)
    {
        return $query->where('meses_prolongacion', '>', 0);
    }

    public function scopeVencidas($query)
    {
        return $query->where('fecha_actualizacion', '<', Carbon::now())
                     ->where('valor_deuda', '>', 0);
    }

    // Eventos del modelo
    protected static function boot()
    {
        parent::boot();

        // Calcular cantidad mensual automáticamente antes de guardar
        static::saving(function ($deuda) {
            if ($deuda->meses_prolongacion > 0 && $deuda->valor_deuda > 0) {
                $deuda->cantidad_mensual = $deuda->valor_deuda / $deuda->meses_prolongacion;
            }
        });
    }

    // Métodos útiles
    public function calcularCantidadMensual()
    {
        if ($this->meses_prolongacion > 0 && $this->valor_deuda > 0) {
            return round($this->valor_deuda / $this->meses_prolongacion, 2);
        }
        return 0;
    }

    public function realizarPago($montoPago)
    {
        if ($montoPago <= 0) {
            throw new \Exception('El monto del pago debe ser mayor a cero');
        }

        if ($montoPago > $this->valor_deuda) {
            throw new \Exception('El monto del pago excede el valor de la deuda');
        }

        $this->valor_deuda -= $montoPago;
        
        // Si se pagó completamente, resetear meses de prolongación
        if ($this->valor_deuda <= 0) {
            $this->valor_deuda = 0;
            $this->meses_prolongacion = 0;
            $this->cantidad_mensual = 0;
        } else {
            // Recalcular cantidad mensual con el nuevo saldo
            if ($this->meses_prolongacion > 0) {
                $this->cantidad_mensual = $this->valor_deuda / $this->meses_prolongacion;
            }
        }

        $this->fecha_actualizacion = Carbon::now();
        $this->save();

        return $this;
    }

    public static function totalPorUsuario($idUsuario, $soloActivas = true)
    {
        $query = self::where('id_usuario', $idUsuario);
        
        if ($soloActivas) {
            $query->activas();
        }
        
        return $query->sum('valor_deuda');
    }

    public static function totalMensualPorUsuario($idUsuario)
    {
        return self::where('id_usuario', $idUsuario)
                   ->activas()
                   ->sum('cantidad_mensual');
    }

    // Accessors
    public function getEstaPagadaAttribute()
    {
        return $this->valor_deuda <= 0;
    }

    public function getPorcentajePagadoAttribute()
    {
        if ($this->valor_deuda <= 0) {
            return 100;
        }
        // Necesitarías guardar el valor original para calcular esto
        return 0;
    }

    public function getMesesRestantesAttribute()
    {
        return $this->meses_prolongacion;
    }

    public function getEstaVencidaAttribute()
    {
        return $this->fecha_actualizacion < Carbon::now() && $this->valor_deuda > 0;
    }
}