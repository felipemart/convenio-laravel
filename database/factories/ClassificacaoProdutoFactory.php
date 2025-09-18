<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\ClassificacaoProduto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassificacaoProduto>
 */
class ClassificacaoProdutoFactory extends Factory
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
            'tipo'      => fake()->numberBetween(1, 4),
            'codigo'    => fake()->unique()->numerify('##############'),
        ];
    }
}
