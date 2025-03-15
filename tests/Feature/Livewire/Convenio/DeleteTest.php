<?php

declare(strict_types = 1);

use App\Livewire\Convenio\Delete;
use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;

test('Deve ser capaz de deletar uma conveniada', function () {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $convenio = Convenio::where('empresa_id', '=', $empresaConvenio->id)->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['convenio' => $convenio])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertDispatched('convenio.deleted');
});

test('deve ter um confirmacao para excluir', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $convenio = Convenio::where('empresa_id', '=', $empresaConvenio->id)->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['convenio' => $convenio])
        ->call('destroy')
        ->assertHasErrors(['confirmDestroy' => 'confirmed']);

    assertNotSoftDeleted('convenios', ['id' => $convenio->id]);
});
test('Usuario convenio nao pode deletar convenio', function (): void {
    $this->seed(RoleSeeder::class);
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $conveniadaUser = User::factory()->withRoles('convenio')->updateEmpresa($empresaConvenio->id)->create();

    $convenio = Convenio::where('empresa_id', '=', $empresaConvenio->id)->first();

    actingAs($conveniadaUser);

    Livewire::test(Delete::class, ['convenio' => $convenio])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertNotDispatched('convenio.deleted');

    assertNotSoftDeleted('convenios', ['id' => $convenio->id]);
});
