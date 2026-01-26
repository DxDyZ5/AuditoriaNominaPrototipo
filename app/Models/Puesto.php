<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Puesto extends Model
{
    protected $table = 'puestos';
    protected $fillable = ['nombre_puesto', 'departamento'];

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'puesto_id');
    }
}
