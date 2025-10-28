<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fak = \Faker\Factory::create('es_ES');
        return [
            "dni" => $fak->dni,
            "nombre" => $fak->name,
            "tfno" => $fak->phoneNumber,
            "edad" => rand(18,100)
        ];
    }
}
