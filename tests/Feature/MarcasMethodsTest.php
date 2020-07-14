<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MarcasMethodsTest extends TestCase
{
    /**
     * 
     * @test
     * @return void
     */
    public function can_show_brands()
    {
        
        $this->get(route('marcas.list'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'data' => []
            ]);
    }
}
