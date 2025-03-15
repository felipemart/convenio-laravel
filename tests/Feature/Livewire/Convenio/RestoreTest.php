<?php

declare(strict_types = 1);

use App\Livewire\Convenio\Restore;
use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

test('deve ser capaz de restaurar  um convenio', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);

    $convenio = Convenio::where('empresa_id', '=', $emprsaConvenio->id)->first();
    $convenio->delete();

    $userOperadora = User::factory()->withRoles('operadora')->updateEmpresa($emprsa->id)->create();
    actingAs($userOperadora);

    Livewire::test(Restore::class)
        ->set('convenio', $convenio)
        ->set('confirmRestore_confirmation', 'RESTAURAR')
        ->call('restore')
        ->assertDispatched('convenio.restored');

    assertNotSoftDeleted('convenios', ['id' => $convenio->id]);

    $convenio->refresh();
    expect($convenio)
        ->restored_at->not->toBeNull()
        ->restoredBy->id->toBe($userOperadora->id);
});

test('deve ter um confirmacao para restaurar', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);

    $convenio = Convenio::where('empresa_id', '=', $emprsaConvenio->id)->first();
    $convenio->delete();

    $userOperadora = User::factory()->withRoles('operadora')->updateEmpresa($emprsa->id)->create();
    actingAs($userOperadora);

    Livewire::test(Restore::class)
        ->set('convenio', $convenio)
        ->call('restore')
        ->assertHasErrors(['confirmRestore' => 'confirmed'])
        ->assertNotDispatched('user.restored');

    assertSoftDeleted('convenios', ['id' => $convenio->id]);
});
