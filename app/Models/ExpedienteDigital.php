<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpedienteDigital extends Model
{
    protected $table = 'expediente_digital';
    protected $fillable = ['empleado_id', 'doc_id', 'ruta_archivo', 'fecha_subida', 'fecha_vencimiento', 'valido', 'usuario_id'];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(DocumentoRequerido::class, 'doc_id');
    }
}
