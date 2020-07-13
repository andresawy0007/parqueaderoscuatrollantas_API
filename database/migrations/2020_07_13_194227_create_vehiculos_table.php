<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forrll_vehiculos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('placa', 20);
            $table->unsignedBigInteger('tipo_vehiculo_id');
            $table->unsignedBigInteger('marca_vehiculo_id');
            
            // Time and softdelete 
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('tipo_vehiculo_id')->references('id')->on('forrll_tipos_vehiculos');
            $table->foreign('marca_vehiculo_id')->references('id')->on('forrll_marcas_vehiculos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forrll_vehiculos');
    }
}
