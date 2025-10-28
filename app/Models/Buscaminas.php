<?php

namespace App\Models;

class Buscaminas {
    public $tablero;
    public $descuento = 0.2;

    public function iniciarTablero($cant) {
        $this->tablero = array_fill(0,$cant,"-");
        return count($this->tablero);
    }

    public function colocarMinas($minas){
        while($minas>0){
            $pos = rand(0,count($this->tablero)-1);
            if ($this->tablero[$pos] == '-'){
                $this->tablero[$pos] = '*';
                $minas--;
            }
        }
    }

    public function calcularPrecio($cant, $precio) {
        return $cant * $precio * (1-$this->descuento);
    }
}
