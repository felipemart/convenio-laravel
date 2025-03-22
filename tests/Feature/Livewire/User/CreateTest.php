<?php

declare(strict_types = 1);

use App\Livewire\User\Create;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('creates a new user successfully', function () {
    $admin = User::factory()->withRoles('admin')->create();

    actingAs($admin);
    Livewire::test(Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('roleSelect', 1) // Assuming role ID 1 exists
        ->set('empresaSelect', 1) // Assuming empresa ID 1 exists
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'johndoe@example.com',
    ]);
});

it('fails to create a user with invalid data', function () {
    $admin = User::factory()->withRoles('admin')->create();

    actingAs($admin);
    Livewire::test(Create::class)
        ->set('name', '')
        ->set('email', 'invalid-email')
        ->set('roleSelect', null)
        ->set('empresaSelect', null)
        ->call('save')
        ->assertHasErrors(['name', 'email', 'roleSelect']);
});
