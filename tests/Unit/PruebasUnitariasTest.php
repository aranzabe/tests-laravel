<?php

namespace Tests\Unit;

use App\Models\Buscaminas;
use PHPUnit\Framework\TestCase;

class PruebasUnitariasTest extends TestCase
{
        public function test_iniciar_tablero()
    {
        $tam = rand(1,10);
        // echo 'Iniciado tablero con '.$tam;
        $b = new Buscaminas($tam);
        $response = $b->iniciarTablero($tam);
        $this->assertEquals($tam, $response);
    }

    public function test_colocar_minas(){
        $b = new Buscaminas();
        $tam = rand(1,5);
        $b->iniciarTablero($tam);

        $minas = rand(1,3);
        $b->colocarMinas($minas);
        $ocurrencias = array_count_values($b->tablero);
        // print_r($ocurrencias);
        // print_r($b->tablero);

        $this->assertEquals($minas, $ocurrencias['*']);
    }

    public function test_calcular_precios(){
        $cantidad = 10;
        $precio = 100;
        $b = new Buscaminas();
        $this->assertEquals($b->calcularPrecio($cantidad, $precio),800);
    }
}
