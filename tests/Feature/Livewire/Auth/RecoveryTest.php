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

test('precisa ter uma rota para recuperação de senha', function (): void {
    get(route('password.recovery'))
        ->assertSeeLivewire('auth.password.recovery')
        ->assertOk();
});

test('deve ser capaz de recuperar a senha', function (): void {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(recovery::class)
        ->assertDontSee('Email enviado com processo de recuperação de senha.')
        ->set('email', $user->email)
        ->call('recuperacaoSenha')
        ->assertSee('Email enviado com processo de recuperação de senha.');

    Notification::assertSentTo($user, EmailRecuperacaoSenha::class);
});

test('certificando-se de que o e-mail é real', function ($value, $rule): void {
    Livewire::test(recovery::class)
        ->set('email', $value)
        ->call('recuperacaoSenha')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'qualerEmail', 'rule' => 'email'],
]);

test('precisa criar recuperação de token', function (): void {
    $user = User::factory()->create();

    Livewire::test(recovery::class)
        ->set('email', $user->email)
        ->call('recuperacaoSenha');

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});
