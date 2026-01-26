<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantillaItem extends Model
{
    protected $table = 'plantilla_items';
    protected $fillable = ['plantilla_id', 'documento_requerido_id'];

    public function plantilla(): BelongsTo
    {
        return $this->belongsTo(PlantillaAuditoria::class, 'plantilla_id');
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(DocumentoRequerido::class, 'documento_requerido_id');
    }
}
