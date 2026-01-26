<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaFinanciera extends Model
{
    protected $table = 'auditoria_financiera';
    protected $fillable = ['empleado_id', 'tipo_error', 'monto_diferencia', 'detalles', 'nomina_id'];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function nomina(): BelongsTo
    {
        return $this->belongsTo(NominaHistorico::class, 'nomina_id');
    }
}
