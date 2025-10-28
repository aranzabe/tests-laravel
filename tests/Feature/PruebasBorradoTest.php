<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PruebasBorradoTest extends TestCase
{
    public function test_borrar()
    {
        $fak = \Faker\Factory::create('es_ES');

        $datos = [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->phoneNumber,
            "edad" => rand(18,100)
        ];

        echo 'Insertando para borrar persona: ';
        print_r ($datos);


        $this->json('post', '/api/insertarpersona', $datos);


        $this->json('delete', "/api/borrarpersona/".$datos["dni"])
            ->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Persona eliminada correctamente.',
            ]);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"])
            ->assertStatus(404)
            ->assertJson([
                'mensaje' => 'Persona no encontrada.',
            ]);
    }

    public function test_borrar2(){
        $fak = \Faker\Factory::create('es_ES');

        $datos = [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->phoneNumber,
            "edad" => rand(18,100)
        ];

        echo 'Insertando para borrar persona: ';
        print_r ($datos);


        //Confirma que la persona fue insertada en la base de datos
        //$this->json('post', '/api/insertarpersona', $datos);
        //También se puede hacer así:
        $response = $this->postJson('/api/insertarpersona', $datos);

        $this->assertDatabaseHas('personas', ['dni' => $datos['dni']]);


        $response = $this->delete("/api/borrarpersona/".$datos["dni"]);
        $response->assertStatus(200);
        $response->assertJson(['mensaje' => 'Persona eliminada correctamente.']);

        $response = $this->delete("/api/borrarpersona/".$datos["dni"]);
        $response->assertStatus(404);
        $response->assertJson(['mensaje' => 'Persona no encontrada.']);
    }
}
