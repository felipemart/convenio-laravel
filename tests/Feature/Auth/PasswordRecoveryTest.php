<?php

use App\Livewire\Auth\Password\Recovery;
use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;

test('needs to have a route to password recovery', function () {
    get(route('auth.password.recovery'))
        ->assertSeeLivewire('auth.password.recovery')
        ->assertOk();
});

it('should be able to recover password', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->assertDontSee('Email enviado com processo de recuperação de senha.')
        ->set('email', $user->email)
        ->call('startPasswordRecovery')
        ->assertSee('Email enviado com processo de recuperação de senha.');

    Notification::assertSentTo($user, PasswordRecoveryNotification::class);

});

test('making sure the email is a real emall', function ($value, $rule) {

    Livewire::test(Recovery::class)
        ->set('email', $value)
        ->call('startPasswordRecovery')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'qualerEmail', 'rule' => 'email'],
]);
