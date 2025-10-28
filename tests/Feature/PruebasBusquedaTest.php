<?php

namespace Tests\Feature;

use App\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PruebasBusquedaTest extends TestCase
{
    //use RefreshDatabase; //Limpia y migra la base de datos antes de cada test

    public function test_buscar_persona() {

        $fak = \Faker\Factory::create('es_ES');

        $datos = [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->numerify('#########'),  //Para tener números sin símbolos y que no falle el test.
            "edad" => rand(18,100)
        ];

        $this->json('post', '/api/insertarpersona', $datos);

        echo 'Insertado para buscar persona: ';
        print_r ($datos);


        $this->json('get', '/api/buscarpersona/'.$datos["dni"])
        ->assertStatus(200)
        ->assertJson(["dni" => $datos["dni"], "nombre" => $datos["nombre"], "tfno" => $datos["tfno"], "edad" => $datos["edad"]])
        ->assertJsonStructure(
            ['dni', 'nombre', "tfno"]);
            // ['dni', 'nombre', "tfno","edad"]);

        //Borramos el caso de prueba.
        $this->json('delete', "/api/borrarpersona/".$datos["dni"]);
    }

    public function test_buscar_persona2() {
        //Oción A)
        //op A.1
        // $fak = \Faker\Factory::create('es_ES');

        // $datos = [
        //     "dni" => $fak->dni,
        //     "nombre" => $fak->name,
        //     "tfno" => $fak->numerify('#########'),  //Para tener números sin símbolos y que no falle el test.
        //     "edad" => rand(18,100)
        // ];

        //op A.2
        //O usamos la factoría pero la opción make que no lo inserta en la base de datos.
        //$datos = Persona::factory()->make()->toArray();

        //Común a la opción A.1 y A.2.
        // $this->json('post', '/api/insertarpersona', $datos);

        // echo 'Insertado para buscar persona: ';
        // print_r ($datos);


        //Opción B)
        $datos = Persona::factory()->create()->toArray();
        echo 'Insertando desde una factoría una persona para probar búsqueda: ';
        print_r ($datos);


        //Común a la opción A y B.
        //Confirma que la persona fue insertada en la base de datos
        $this->assertDatabaseHas('personas', ['dni' => $datos['dni']]);

        //$response = $this->get('/api/buscarpersona/'.$datos["dni"]);
        //O tambén se puede hacer así:
        $response = $this->getJson('/api/buscarpersona/'.$datos["dni"]);
        $response->assertStatus(200);
        $response->assertJson(["dni" => $datos["dni"],
                               "nombre" => $datos["nombre"],
                               "tfno" => $datos["tfno"],
                               "edad" => $datos["edad"]]);

        $this->json('delete', "/api/borrarpersona/".$datos["dni"]);//Esta opción es común a las dos opciones.
    }
}
