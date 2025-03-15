<?php

declare(strict_types = 1);

use App\Livewire\Convenio\Update;
use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve acessar os dadaos da conveniada', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);

    $convenio = Convenio::where('empresa_id', '=', $emprsaConvenio->id)->first();

    $userOperadora = User::factory()->withRoles('operadora')->updateEmpresa($emprsa->id)->create();
    actingAs($userOperadora);

    Livewire::test(Update::class, ['id' => $convenio->id])
        ->assertSee($emprsaConvenio->cnpj)
        ->assertSee($emprsaConvenio->nome_fantasia)
        ->assertSee($emprsaConvenio->razao_social)
        ->assertSee($emprsaConvenio->logradouro)
        ->assertSee($emprsaConvenio->bairro)
        ->assertSee($emprsaConvenio->cep)
        ->assertSee($emprsaConvenio->uf)
        ->assertSee($emprsaConvenio->cidade)
        ->assertSee($emprsaConvenio->email)
        ->assertOk();
});
