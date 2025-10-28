<?php

namespace Tests\Feature;

use App\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PruebasInsercionTest extends TestCase
{
    public function test_insertar()
    {
        $fak = \Faker\Factory::create('es_ES');

        $datos = [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->phoneNumber,
            "edad" => rand(18,100)
        ];

        echo 'Insertando persona para probar inserción: ';
        print_r ($datos);

        $this->json('post', '/api/insertarpersona', $datos)
            ->assertStatus(201)
            ->assertJsonStructure(['dni', 'nombre', "tfno"])
            ->assertJson(["dni" => $datos["dni"], "nombre" => $datos["nombre"], "tfno" => $datos["tfno"]]);



        $this->json('post', '/api/insertarpersona', $datos)
            ->assertStatus(404)
            ->assertJson([
                'mens' => 'Clave duplicada',
            ]);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"]);
    }

    public function test_insertar2()
    {
        //Opción A)
        // op A.1
        //O usamos el faker aquí.
        // $fak = \Faker\Factory::create('es_ES');
        // $datos = [
        //     "dni" => $fak->dni,
        //     "nombre" => $fak->name,
        //     "tfno" => $fak->phoneNumber,
        //     "edad" => rand(18,100)
        // ];
        // op A.2
        //O usamos la factoría pero la opción make que no lo inserta en la base de datos.
        $datos = Persona::factory()->make()->toArray();

        $response = $this->post('/api/insertarpersona', $datos);
        $response->assertStatus(201);
        $response->assertJsonStructure(['dni', 'nombre', "tfno", "edad"]);
        $response->assertJson(["dni" => $datos["dni"], "nombre" => $datos["nombre"], "tfno" => $datos["tfno"], "edad" => $datos["edad"]]);

        echo 'Insertando persona para probar inserción: ';
        print_r ($datos);

        //Opción B)
        //Otra opción, usar la factoría con create que sí lo inserta en la base de datos.
        // $datos = Persona::factory()->create()->toArray();
        // echo '------->  Insertando desde una factoría una persona para probar inserción, clave duplicada: ';
        // print_r ($datos);


        //Común a la opción A y B.
        $response = $this->post('/api/insertarpersona', $datos);
        $response->assertStatus(404);
        $response->assertJson(['mens' => 'Clave duplicada']);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"]); //Esta opción es común a las dos opciones.
    }
}
