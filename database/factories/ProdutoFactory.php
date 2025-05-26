<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descricao' => fake()->word(),
            'ean_id'    => fake()->unique()->numerify('##############'),
            'codigo'    => fake()->unique()->numerify('##############'),
            'pr_maximo' => fake()->randomFloat(2, 1, 100),
        ];
    }
}
