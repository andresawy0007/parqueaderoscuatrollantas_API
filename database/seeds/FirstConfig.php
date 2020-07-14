<?php

use Illuminate\Database\Seeder;

class FirstConfig extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Marcas de vehículos

        DB::table('forrll_marcas_vehiculos')->insert([
            'nombre' => 'Jeep',
        ]);
        DB::table('forrll_marcas_vehiculos')->insert([
            'nombre' => 'Lancia',
        ]);
        DB::table('forrll_marcas_vehiculos')->insert([
            'nombre' => 'Mazda',
        ]);
        DB::table('forrll_marcas_vehiculos')->insert([
            'nombre' => 'Nissan',
        ]);

        // Tipos de vehículos
        DB::table('forrll_tipos_vehiculos')->insert([
            'nombre' => 'Carro',
        ]);
        DB::table('forrll_tipos_vehiculos')->insert([
            'nombre' => 'Moto',
        ]);

        // Propietarios
        DB::table('forrll_propietarios_vehiculos')->insert([
            'nombres' => 'Andrés Guerrero Andrade',
            'cedula' => '1045214553'
        ]);
        DB::table('forrll_propietarios_vehiculos')->insert([
            'nombres' => 'Ivan Henrrique',
            'cedula' => '1042514778'
        ]);
    }
}
