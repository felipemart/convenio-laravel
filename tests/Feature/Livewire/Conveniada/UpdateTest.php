<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Update;
use App\Models\Empresa;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve acessar o perfil da conveniada', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($empresaConvenio->id);

    $conveniada = $empresaConveniada->conveniadas()->first();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $conveniada->id])
        ->assertSet('razao_social', $empresaConveniada->razao_social)
        ->assertSet('nome_fantasia', $empresaConveniada->nome_fantasia)
        ->assertSet('logradouro', $empresaConveniada->logradouro)
        ->assertSet('bairro', $empresaConveniada->bairro)
        ->assertSet('cep', $empresaConveniada->cep)
        ->assertSet('uf', $empresaConveniada->uf)
        ->assertSet('cidade', $empresaConveniada->cidade)
        ->assertSet('email', $empresaConveniada->email)
        ->assertOk();
});
