<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propietarios_vehiculos extends Model
{
    use SoftDeletes;
    protected $table = 'forrll_propietarios_vehiculos';
    
    public function vehiculos()
    {
        return $this->hasMany('App\Models\Vehiculos', 'tipo_vehiculo_id');
    }
}
