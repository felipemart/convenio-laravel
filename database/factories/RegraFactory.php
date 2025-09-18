<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Regra;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Regra>
 */
class RegraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'grupo_desconto_id' => fake()->numberBetween(1, 10),
            'permite_venda'     => fake()->numberBetween(0, 1),
            'descrento_com_crm' => fake()->randomFloat(2, 0, 100),
            'desconto_sem_crm'  => fake()->randomFloat(2, 0, 100),
            'subsidio_com_crm'  => fake()->randomFloat(2, 0, 100),
            'subsidio_sem_crm'  => fake()->randomFloat(2, 0, 100),
            'validade_receita'  => fake()->randomFloat(2, 0, 100),
            'crm'               => fake()->boolean(),
            'receita'           => fake()->boolean(),
            'ativo'             => fake()->boolean(),
            'tipo'              => fake()->numberBetween(0, 1),
            'dt_inicio'         => fake()->dateTime(),
            'dt_fim'            => fake()->dateTime(),
        ];
    }
}
