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
        ->call('lgoin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);

});

it('should make sure the email and password', function () {
    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('lgoin')
        ->assertHasErrors(['crendenciaisInvalidas'])
        ->assertSee('Credenciais inválidas.');

});

it('should make sure that rate limiter is working', function () {
    $usrer = User::factory()->create();

    for ($i = 0; $i < 5; $i++) {
        Livewire::test(Login::class)
            ->set('email', $usrer->email)
            ->set('password', 'wrong-password')
            ->call('lgoin')
            ->assertHasErrors(['crendenciaisInvalidas']);
    }

    Livewire::test(Login::class)
        ->set('email', $usrer->email)
        ->set('password', 'wrong-password')
        ->call('lgoin')
        ->assertHasErrors(['rateLimiter']);

});
