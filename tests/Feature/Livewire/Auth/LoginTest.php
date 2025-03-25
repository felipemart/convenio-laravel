<?php

declare(strict_types = 1);

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to login', function (): void {
    $user = User::factory()->create([
        'email'    => 'johndoe@example.com',
        'password' => 'password',
    ]);
    $user->giveRole('guest');

    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('lgoin')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});

it('should validate the email and password', function (): void {
    Livewire::test(Login::class)
        ->set('email', 'johndoe@example.com')
        ->set('password', 'password')
        ->call('lgoin')
        ->assertHasErrors(['crendenciaisInvalidas'])
        ->assertSee('Credenciais inválidas.');
});

it('should ensure that rate limiter is working', function (): void {
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
