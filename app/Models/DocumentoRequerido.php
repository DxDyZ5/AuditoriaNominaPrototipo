<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentoRequerido extends Model
{
    protected $table = 'documentos_requeridos';
    protected $fillable = ['nombre_doc', 'es_obligatorio', 'requiere_vencimiento', 'dias_aviso'];

    public function archivos(): HasMany
    {
        return $this->hasMany(ExpedienteDigital::class, 'doc_id');
    }
}
