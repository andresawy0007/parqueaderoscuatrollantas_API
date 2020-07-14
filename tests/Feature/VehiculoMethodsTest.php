<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class VehiculoMethodsTest extends TestCase
{
    /**
     * 
     * @test
     * @return void
     */
    public function can_create_vehicle()
    {
        $cedula = mt_rand(100000000, 999999999);
        $placa = "a".mt_rand(1000, 9999);
        $data = [
            "propietario_cedula" => $cedula,
            "propietario_nombres" => 'Usuario test',
            "tipo" => 1,
            "marca" => 2,
            "placa" => $placa
        ];
        $this->json('POST', route('vehiculos.new'), $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    'propietario',
                    'tipo',
                    'marca',
                    'placa',
                ]
            ]);

    }
    /**
     * 
     * @test
     * @return void
     */
    public function can_validate_vehicle_exist()
    {
        $cedula = mt_rand(100000000, 999999999);
        $placa = "a".mt_rand(1000, 9999);
        $data = [
            "propietario_cedula" => $cedula,
            "propietario_nombres" => 'Usuario test',
            "tipo" => 1,
            "marca" => 2,
            "placa" => $placa
        ];
        $this->json('POST', route('vehiculos.new'), $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    'propietario',
                    'tipo',
                    'marca',
                    'placa',
                ]
            ]);
        
        $this->json('POST', route('vehiculos.new'), $data)
            ->assertStatus(200)
            ->assertJson([
                'result' => false,
                'code' => 'vehiculo_exist'
            ]);
    }

    
    /**
     * 
     * @test
     * @return void
     */
    public function can_find_vehicle_placa()
    {
        $placa = "A4173";
        
        $this->get(route('vehiculos.search')."?placa=".$placa)
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    'propietario' => [
                        'nombres',
                        "cedula"
                    ],
                    'vehiculos' 
                ]
            ]);
    }
    /**
     * 
     * @test
     * @return void
     */
    public function can_find_vehicle_propietario_cedula()
    {
        $cedula = "1234567898";
        
        $this->get(route('vehiculos.search')."?propietario_cedula=".$cedula)
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    'propietario' => [
                        'nombres',
                        "cedula"
                    ],
                    'vehiculos'
                ]
            ]);
    }
    /**
     * 
     * @test
     * @return void
     */
    public function can_find_vehicle_propietario_nombre()
    {
        $nombres = "Usuario test";
        
        $this->get(route('vehiculos.search')."?propietario_nombres=".$nombres)
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => [
                    'propietario' => [
                        'nombres',
                        "cedula"
                    ],
                    'vehiculos'
                ]
            ]);
    }
}
