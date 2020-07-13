<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculos extends Model
{
    use SoftDeletes;
    protected $table = 'forrll_vehiculos';
    
    public function tipo_vehiculo()
    {
        return $this->belongsTo('App\Models\Tipos_vehiculos', 'tipo_vehiculo_id');
    }
    public function marca_vehiculo()
    {
        return $this->belongsTo('App\Models\Marcas_vehiculos', 'marca_vehiculo_id');
    }
    public function propietario_vehiculo()
    {
        return $this->belongsTo('App\Models\Propietarios_vehiculos', 'propietario_vehiculo_id');
    }
}
