<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tipos_vehiculos extends Model
{
    use SoftDeletes;
    protected $table = 'forrll_tipos_vehiculos';
    //
}
