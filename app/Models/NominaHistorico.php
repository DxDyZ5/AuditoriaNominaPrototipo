<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NominaHistorico extends Model
{
    protected $table = 'nominas_historico';
    protected $fillable = ['mes', 'año', 'total_bruto', 'total_neto'];

    public function pagos(): HasMany
    {
        return $this->hasMany(DetallePago::class, 'nomina_id');
    }
}
