<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\ClassificacaoProduto;
use App\Models\Produto;
use App\Models\Regra;
use App\Models\RegrasAssociada;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RegrasAssociada>
 */
class RegrasAssociadaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'regra_id'         => Regra::factory(),
            'produto_id'       => Produto::factory(),
            'classificacao_id' => ClassificacaoProduto::factory(),
        ];
    }
}
