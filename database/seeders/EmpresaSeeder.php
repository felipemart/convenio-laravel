<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Operadora;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::create([
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

        ]);
    }

    public function configure()
    {
        return $this->state(function (array $attributes) {
            return [
                // outros campos
            ];
        })->afterCreating(function (Empresa $empresa) {
            if (isset($attributes['is_operadora']) && $attributes['is_operadora'] === true) {
                Operadora::factory()->create(['empresa_id' => $empresa->id]);
            }
        });
    }
}
