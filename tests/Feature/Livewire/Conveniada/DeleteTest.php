<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Delete;
use App\Models\Conveniada;
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

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($empresaConvenio->id);

    $conveniada = Conveniada::where('empresa_id', '=', $empresaConveniada->id)->first();

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

    $conveniada = Conveniada::where('empresa_id', '=', $empresaConveniada->id)->first();

    actingAs($admin);

    Livewire::test(Delete::class, ['conveniada' => $conveniada])
        ->call('destroy')
        ->assertHasErrors(['confirmDestroy' => 'confirmed']);

    assertNotSoftDeleted('conveniadas', ['id' => $conveniada->id]);
});
test('Usuario conveniada nao pode deletar conveniada', function (): void {
    $this->seed(RoleSeeder::class);
    $conveniadaUser = User::factory()->withRoles('conveniada')->create();
    $empresa        = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($empresaConvenio->id);

    $conveniada = Conveniada::where('empresa_id', '=', $empresaConveniada->id)->first();

    actingAs($conveniadaUser);

    Livewire::test(Delete::class, ['conveniada' => $conveniada])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertNotDispatched('user.deleted');

    assertNotSoftDeleted('conveniadas', ['id' => $conveniada->id]);
});
