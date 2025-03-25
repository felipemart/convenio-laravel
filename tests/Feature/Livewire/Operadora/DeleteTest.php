<?php

declare(strict_types = 1);

use App\Livewire\Operadora\Delete;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

test('Should be able to delete an operator', function () {
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

test('should have a confirmation to delete', function (): void {
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
test('Operator user cannot delete operator', function (): void {
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
