<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marcas_vehiculos extends Model
{
    use SoftDeletes;
    protected $table = 'forrll_marcas_vehiculos';
    
    public function vehiculos()
    {
        return $this->hasMany('App\Models\Vehiculos', 'marca_vehiculo_id');
    }
}
