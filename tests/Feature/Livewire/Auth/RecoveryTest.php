<?php

declare(strict_types = 1);

use App\Livewire\Auth\Password\recovery;
use App\Models\User;
use App\Notifications\EmailRecuperacaoSenha;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;

test('must have a route for password recovery', function (): void {
    get(route('password.recovery'))
        ->assertSeeLivewire('auth.password.recovery')
        ->assertOk();
});

test('should be able to recover the password', function (): void {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(recovery::class)
        ->assertDontSee('Email enviado com processo de recuperação de senha.')
        ->set('email', $user->email)
        ->call('recuperacaoSenha')
        ->assertSee('Email enviado com processo de recuperação de senha.');

    Notification::assertSentTo($user, EmailRecuperacaoSenha::class);
});

test('making sure the email is real', function ($value, $rule): void {
    Livewire::test(recovery::class)
        ->set('email', $value)
        ->call('recuperacaoSenha')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'qualerEmail', 'rule' => 'email'],
]);

test('must create recovery token', function (): void {
    $user = User::factory()->create();

    Livewire::test(recovery::class)
        ->set('email', $user->email)
        ->call('recuperacaoSenha');

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});
