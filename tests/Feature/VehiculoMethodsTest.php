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
    public function can_create_vehivle()
    {
        $cedula = '';

        $formData = [
            "propietario_cedula" => '1234567898',
            "propietario_nombres" => 'Usuario test',
            "tipo" => 1,
            "marca" => 1,
            "placa" => "12asj"
        ];
        $this->post(route('vehiculos.new'), $formData)
            ->assertStatus(201)
            ->assertJson($data);
    }
}
