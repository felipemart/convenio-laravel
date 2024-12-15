<?php

use App\Livewire\Auth\Password\RecuperacaoSenha;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;

test('precisa receber um token validos', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(RecuperacaoSenha::class)
        ->set('email', $user->email)
        ->call('recuperacaoSenha');

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) {
        get(route('password.reset') . '?token=' . $notification->token)
            ->assertSuccessful();

        get(route('password.reset') . '?token=invalido-token')
            ->assertRedirect(route('login'));

        return true;
    });

});
