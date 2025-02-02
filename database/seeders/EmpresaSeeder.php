<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Empresa;
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
}
