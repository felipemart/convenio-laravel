<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\GrupoDesconto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GrupoDesconto>
 */
class GrupoDescontoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descricao'    => fake()->word(),
            'ativo'        => fake()->boolean(),
            'operadora_id' => fake()->numberBetween(1, 10),
        ];
    }
}
