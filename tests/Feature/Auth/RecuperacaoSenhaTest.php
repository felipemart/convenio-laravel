<?php

use App\Models\User;
use App\Notifications\RecuperacaoSenha;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, get};

test('precisa ter uma rota para recuperação de senha', function () {
    get(route('password.recuperar'))
        ->assertSeeLivewire('auth.password.recuperacao-senha')
        ->assertOk();
});

test('deve ser capaz de recuperar a senha', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(\App\Livewire\Auth\Password\RecuperacaoSenha::class)
        ->assertDontSee('Email enviado com processo de recuperação de senha.')
        ->set('email', $user->email)
        ->call('recuperacaoSenha')
        ->assertSee('Email enviado com processo de recuperação de senha.');

    Notification::assertSentTo($user, RecuperacaoSenha::class);

});

test('certificando-se de que o e-mail é real', function ($value, $rule) {

    Livewire::test(RecuperacaoSenha::class)
        ->set('email', $value)
        ->call('recuperacaoSenha')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'qualerEmail', 'rule' => 'email'],
]);

test('precisa criar recuperação de token', function () {
    $user = User::factory()->create();

    Livewire::test(RecuperacaoSenha::class)
        ->set('email', $user->email)
        ->call('recuperacaoSenha');

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);

});
