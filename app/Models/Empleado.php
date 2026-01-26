<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = ['nombre', 'puesto_id', 'fecha_ingreso', 'estado', 'salario_diario', 'tipo_pago', 'cuenta_bancaria_hash', 'fecha_ultimo_incremento', 'bloquear_pago'];

    public function puesto(): BelongsTo
    {
        return $this->belongsTo(Puesto::class, 'puesto_id');
    }

    public function expediente(): HasMany
    {
        return $this->hasMany(ExpedienteDigital::class, 'empleado_id');
    }
}
