<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'historial';
    protected $fillable = ['usuario_id', 'accion', 'tabla', 'registro_id', 'detalles'];
}
