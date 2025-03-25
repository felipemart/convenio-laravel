<?php

declare(strict_types = 1);

use App\Livewire\Operadora\Restore;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

test('should be able to restore an operator', function (): void {
    $this->seed(RoleSeeder::class);
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();
    $operadora->delete();

    $user = User::factory()->withRoles('admin')->create();
    actingAs($user);

    Livewire::test(Restore::class)
        ->set('operadora', $operadora)
        ->set('confirmRestore_confirmation', 'RESTAURAR')
        ->call('restore')
        ->assertDispatched('operadora.restored');

    assertNotSoftDeleted('operadoras', ['id' => $operadora->id]);

    $operadora->refresh();
    expect($operadora)
        ->restored_at->not->toBeNull()
        ->restoredBy->id->toBe($user->id);
});

test('should have a confirmation to restore', function (): void {
    $this->seed(RoleSeeder::class);
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();
    $operadora->delete();

    $user = User::factory()->withRoles('admin')->create();
    actingAs($user);

    Livewire::test(Restore::class)
        ->set('operadora', $operadora)
        ->call('restore')
        ->assertHasErrors(['confirmRestore' => 'confirmed'])
        ->assertNotDispatched('operadora.restored');

    assertSoftDeleted('operadoras', ['id' => $operadora->id]);
});
