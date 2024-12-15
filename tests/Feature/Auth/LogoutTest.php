<?php

use App\Livewire\Auth\Logout;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve desconectar o usuário', function () {

    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Logout::class)
        ->call('logout')
        ->assertRedirect(route('login'));

    expect(auth()->guest())->toBeTrue();
});
