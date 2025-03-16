<?php

declare(strict_types = 1);

use App\Livewire\Operadora\Show;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\actingAs;

test('deve ser capaz de ver o cadastro da operadora', function (): void {
    $this->seed(RoleSeeder::class);
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();

    $user = User::factory()->withRoles('admin')->create();
    actingAs($user);

    Livewire::test(Show::class, ['id' => $operadora->id])
        ->assertSee($empresa->cnpj)
        ->assertSee($empresa->nome_fantasia)
        ->assertSee($empresa->razao_social)
        ->assertSee($empresa->logradouro)
        ->assertSee($empresa->bairro)
        ->assertSee($empresa->cep)
        ->assertSee($empresa->uf)
        ->assertSee($empresa->cidade)
        ->assertSee($empresa->email);
});
