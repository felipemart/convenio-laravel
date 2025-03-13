<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Update;
use App\Models\Conveniada;
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

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($emprsaConvenio->id);
    $conveniada = Conveniada::where('empresa_id', '=', $empresaConveniada->id)->first();

    $userConvenio = User::factory()->withRoles('convenio')->updateEmpresa($emprsaConvenio->id)->create();
    actingAs($userConvenio);

    Livewire::test(Update::class, ['id' => $conveniada->id])
        ->assertSee($empresaConveniada->cnpj)
        ->assertSee($empresaConveniada->nome_fantasia)
        ->assertSee($empresaConveniada->razao_social)
        ->assertSee($empresaConveniada->logradouro)
        ->assertSee($empresaConveniada->bairro)
        ->assertSee($empresaConveniada->cep)
        ->assertSee($empresaConveniada->uf)
        ->assertSee($empresaConveniada->cidade)
        ->assertSee($empresaConveniada->email)
        ->assertOk();
});
