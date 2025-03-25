<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Restore;
use App\Models\Conveniada;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

test('should be able to restore a conveniada', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($emprsaConvenio->id);
    $conveniada = $empresaConveniada->conveniadas()->first();
    $conveniada->delete();

    $userConvenio = User::factory()->withRoles('convenio')->updateEmpresa($emprsaConvenio->id)->create();
    actingAs($userConvenio);

    Livewire::test(Restore::class)
        ->set('conveniada', $conveniada)
        ->set('confirmRestore_confirmation', 'RESTAURAR')
        ->call('restore')
        ->assertDispatched('conveniada.restored');

    assertNotSoftDeleted('conveniadas', ['id' => $conveniada->id]);

    $conveniada->refresh();
    expect($conveniada)
        ->restored_at->not->toBeNull()
        ->restoredBy->id->toBe($userConvenio->id);
});

test('should have a confirmation to restore', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($emprsaConvenio->id);
    $conveniada = Conveniada::where('empresa_id', '=', $empresaConveniada->id)->first();
    $conveniada->delete();

    $userConvenio = User::factory()->withRoles('convenio')->updateEmpresa($emprsaConvenio->id)->create();
    actingAs($userConvenio);

    Livewire::test(Restore::class)
        ->set('conveniada', $conveniada)
        ->call('restore')
        ->assertHasErrors(['confirmRestore' => 'confirmed'])
        ->assertNotDispatched('user.restored');

    assertSoftDeleted('conveniadas', ['id' => $conveniada->id]);
});
