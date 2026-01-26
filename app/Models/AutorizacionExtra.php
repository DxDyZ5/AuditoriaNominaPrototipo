<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutorizacionExtra extends Model
{
    protected $table = 'autorizaciones_extras';
    protected $fillable = ['empleado_id', 'concepto', 'monto', 'usuario_autorizo_id'];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
