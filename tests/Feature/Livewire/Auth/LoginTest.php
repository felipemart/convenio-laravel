<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to login', function () {
    $user = User::factory()->create([
        'email'    => 'johndoe@example.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('tryLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);

});

it('should make sure the email and password', function () {
    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('tryLogin')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee('Credenciais inválidas.');

});
