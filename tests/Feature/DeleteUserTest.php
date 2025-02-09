<?php

declare(strict_types = 1);

use App\Livewire\Users\Delete;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

test('deve ser capaz de deletar um usuario', function (): void {
    $admin      = User::factory()->withRoles('admin')->create();
    $userDelete = User::factory()->create();

    actingAs($admin);

    Livewire::test(Delete::class, ['user' => $userDelete])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertDispatched('user.deleted');

    assertSoftDeleted('users', ['id' => $userDelete->id]);

    $userDelete->refresh();
    expect($userDelete)
        ->deletedBy->id->toBe($admin->id);
});

test('deve ter um confirmacao para excluir', function (): void {
    $admin      = User::factory()->withRoles('admin')->create();
    $userDelete = User::factory()->create();

    actingAs($admin);

    Livewire::test(Delete::class, ['user' => $userDelete])
        ->call('destroy')
        ->assertHasErrors(['confirmDestroy' => 'confirmed']);

    assertNotSoftDeleted('users', ['id' => $userDelete->id]);
});
test('Nao pode deletar o usuario que esta logado', function (): void {
    $admin = User::factory()->withRoles('admin')->create();

    actingAs($admin);

    Livewire::test(Delete::class, ['user' => $admin])
        ->set('confirmDestroy_confirmation', 'DELETAR')
        ->call('destroy')
        ->assertNotDispatched('user.deleted');

    assertNotSoftDeleted('users', ['id' => $admin->id]);
});
