<?php

declare(strict_types = 1);

use App\Livewire\Convenio\Update;
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

    $convenio = $empresaConvenio->convenios()->first();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $convenio->id])
        ->assertSet('razao_social', $empresaConvenio->razao_social)
        ->assertSet('nome_fantasia', $empresaConvenio->nome_fantasia)
        ->assertSet('logradouro', $empresaConvenio->logradouro)
        ->assertSet('bairro', $empresaConvenio->bairro)
        ->assertSet('cep', $empresaConvenio->cep)
        ->assertSet('uf', $empresaConvenio->uf)
        ->assertSet('cidade', $empresaConvenio->cidade)
        ->assertSet('email', $empresaConvenio->email)
        ->assertOk();
});
