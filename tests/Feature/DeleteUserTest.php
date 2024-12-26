<?php

use App\Livewire\Users\Delete;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, assertNotSoftDeleted, assertSoftDeleted};

test('deve ser capaz de deletar um usuario', function () {
    $admin      = User::factory()->withRoles('admin')->create();
    $userDelete = User::factory()->create();

    actingAs($admin);

    Livewire::test(Delete::class, ['user' => $userDelete])
        ->set('confirmDestroy_confirmation', 'DELETE')
        ->call('destroy')
        ->assertDispatched('user.deleted');

    assertSoftDeleted('users', ['id' => $userDelete->id]);
});

test('deve ter um confirmacao para excluir', function () {
    $admin      = User::factory()->withRoles('admin')->create();
    $userDelete = User::factory()->create();

    actingAs($admin);

    Livewire::test(Delete::class, ['user' => $userDelete])
        ->call('destroy')
        ->assertHasErrors(['confirmDestroy' => 'confirmed']);

    assertNotSoftDeleted('users', ['id' => $userDelete->id]);
});
