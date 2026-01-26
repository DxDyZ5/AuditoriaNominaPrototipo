<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePago extends Model
{
    protected $table = 'detalle_pago';
    protected $fillable = ['nomina_id', 'empleado_id', 'monto_pagado', 'metodo_pago'];

    public function nomina(): BelongsTo
    {
        return $this->belongsTo(NominaHistorico::class, 'nomina_id');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
