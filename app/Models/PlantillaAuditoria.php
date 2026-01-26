<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlantillaAuditoria extends Model
{
    protected $table = 'plantillas_auditoria';
    protected $fillable = ['nombre', 'descripcion'];

    public function items(): HasMany
    {
        return $this->hasMany(PlantillaItem::class, 'plantilla_id');
    }
}
