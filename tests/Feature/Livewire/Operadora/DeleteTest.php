<?php

declare(strict_types = 1);

use App\Livewire\Operadora\Delete;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

test('Deve ser capaz de deletar uma operadora', function () {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['operadora' => $operadora])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertDispatched('operadora.deleted');

    assertSoftDeleted('operadoras', ['id' => $operadora->id]);
});

test('deve ter um confirmacao para excluir', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['convenio' => $operadora])
        ->call('destroy')
        ->assertHasErrors(['confirmDestroy' => 'confirmed']);

    assertNotSoftDeleted('operadoras', ['id' => $operadora->id]);
});
test('Usuario operadora nao pode deletar operadora', function (): void {
    $this->seed(RoleSeeder::class);
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadoraUser = User::factory()->withRoles('operadora')->updateEmpresa($empresa->id)->create();

    $operadora = $empresa->operadora()->first();

    actingAs($operadoraUser);

    Livewire::test(Delete::class, ['operadora' => $operadora])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertNotDispatched('operadora.deleted');

    assertNotSoftDeleted('operadoras', ['id' => $operadora->id]);
});
