<?php

declare(strict_types = 1);

use App\Livewire\Convenio\Delete;
use App\Models\Empresa;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;

test('Should be able to delete a convenio', function () {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $convenio = $empresaConvenio->convenios()->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['convenio' => $convenio])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertDispatched('convenio.deleted');
});

test('should have a confirmation to delete', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $convenio = $empresaConvenio->convenios()->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['convenio' => $convenio])
        ->call('destroy')
        ->assertHasErrors(['confirmDestroy' => 'confirmed']);

    assertNotSoftDeleted('convenios', ['id' => $convenio->id]);
});
