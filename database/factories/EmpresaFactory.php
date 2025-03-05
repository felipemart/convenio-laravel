<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\Operadora;
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

    public function withOperadora(Operadora $operadora): static
    {
        return $this->afterCreating(function (Empresa $empresa) use ($operadora): void {
            $empresa->giveOperadora();
        });
    }

    public function withConvenio(Convenio $convenio): static
    {
        return $this->afterCreating(function (Empresa $empresa) use ($convenio): void {
            $empresa->giveConvenio($convenio);
        });
    }

    public function withConveniada(Conveniada $conveniada): static
    {
        return $this->afterCreating(function (Empresa $empresa) use ($conveniada): void {
            $empresa->giveConveniada($conveniada);
        });
    }
}
