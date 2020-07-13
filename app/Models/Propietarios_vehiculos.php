<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Propietarios_vehiculos extends Model
{
    use SoftDeletes;
    protected $table = 'forrll_propietarios_vehiculos';
    //
}
