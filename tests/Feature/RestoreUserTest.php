<?php

declare(strict_types = 1);

use App\Livewire\Users\Restore;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

test('deve ser capaz de restaurar  um usuario', function (): void {
    $admin       = User::factory()->withRoles('admin')->create();
    $userRestore = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Restore::class)
        ->set('user', $userRestore)
        ->set('confirmRestore_confirmation', 'RESTAURAR')
        ->call('restore')
        ->assertDispatched('user.restored');

    assertNotSoftDeleted('users', ['id' => $userRestore->id]);

    $userRestore->refresh();
    expect($userRestore)
        ->restored_at->not->toBeNull()
        ->restoredBy->id->toBe($admin->id);
});

test('deve ter um confirmacao para restaurar', function (): void {
    $admin       = User::factory()->withRoles('admin')->create();
    $userRestore = User::factory()->deleted()->create();

    actingAs($admin);

    Livewire::test(Restore::class)
        ->set('user', $userRestore)
        ->call('restore')
        ->assertHasErrors(['confirmRestore' => 'confirmed'])
        ->assertNotDispatched('user.restored');

    assertSoftDeleted('users', ['id' => $userRestore->id]);
});
