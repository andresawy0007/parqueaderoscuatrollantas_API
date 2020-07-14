<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropietariosVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forrll_propietarios_vehiculos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombres');
            $table->integer('cedula');

            // Time and softdelete 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forrll_propietarios_vehiculos');
    }
}
