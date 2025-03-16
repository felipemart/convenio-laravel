<?php

declare(strict_types = 1);

use App\Livewire\Operadora\Update;
use App\Models\Empresa;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve acessar o perfil da conveniada', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $operadora->id])
        ->assertSet('razao_social', $empresa->razao_social)
        ->assertSet('nome_fantasia', $empresa->nome_fantasia)
        ->assertSet('logradouro', $empresa->logradouro)
        ->assertSet('bairro', $empresa->bairro)
        ->assertSet('cep', $empresa->cep)
        ->assertSet('uf', $empresa->uf)
        ->assertSet('cidade', $empresa->cidade)
        ->assertSet('email', $empresa->email)
        ->assertOk();
});
