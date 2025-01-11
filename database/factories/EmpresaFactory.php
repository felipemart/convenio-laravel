<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cnpj'                => fake()->unique()->numerify('##############'),
            'nome_fantasia'       => fake()->company(),
            'razao_social'        => fake()->company(),
            'abreviatura'         => fake()->company(),
            'cep'                 => fake()->postcode(),
            'logradouro'          => fake()->streetName(),
            'bairro'              => fake()->city(),
            'cidade'              => fake()->city(),
            'uf'                  => 'SP',
            'telefone'            => fake()->phoneNumber(),
            'email'               => fake()->email(),
            'inscricao_estadual'  => fake()->unique()->numerify('##############'),
            'inscricao_municipal' => fake()->unique()->numerify('##############'),
        ];
    }
}
