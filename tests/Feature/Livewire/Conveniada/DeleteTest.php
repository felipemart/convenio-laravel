<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Delete;
use App\Models\Empresa;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;

test('Should be able to delete a conveniada', function () {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($empresaConvenio->id);

    $conveniada = $empresaConveniada->conveniadas()->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['conveniada' => $conveniada])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertDispatched('conveniada.deleted');
});

test('should have a confirmation to delete', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($empresaConvenio->id);

    $conveniada = $empresaConveniada->conveniadas()->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['conveniada' => $conveniada])
        ->call('destroy')
        ->assertHasErrors(['confirmDestroy' => 'confirmed']);

    assertNotSoftDeleted('conveniadas', ['id' => $conveniada->id]);
});
