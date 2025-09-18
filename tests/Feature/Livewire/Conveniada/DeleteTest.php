<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Delete;
use App\Models\Empresa;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;

test('Deve ser capaz de deletar uma conveniada', function (): void {
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

test('deve ter um confirmacao para excluir', function (): void {
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
