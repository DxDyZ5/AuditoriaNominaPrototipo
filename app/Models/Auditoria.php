<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auditoria extends Model
{
    protected $table = 'auditorias';
    protected $fillable = ['empleado_id', 'auditor_id', 'fecha_auditoria', 'resultado_porcentaje', 'plantilla_id'];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function hallazgos(): HasMany
    {
        return $this->hasMany(Hallazgo::class, 'auditoria_id');
    }
}
