<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hallazgo extends Model
{
    protected $table = 'hallazgos';
    protected $fillable = ['auditoria_id', 'doc_id', 'tipo', 'descripcion', 'estado'];

    public function auditoria(): BelongsTo
    {
        return $this->belongsTo(Auditoria::class, 'auditoria_id');
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(DocumentoRequerido::class, 'doc_id');
    }
}
