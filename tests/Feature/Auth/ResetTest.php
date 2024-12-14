<?php

use App\Livewire\Auth\Password\{Recovery};
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;

test('need to receive a valid email and token', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->set('email', $user->email)
        ->call('startPasswordRecovery');

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) {
        get(route('password.reset') . '?token=' . $notification->token)
            ->assertSuccessful();

        get(route('password.reset') . '?token=invalid-token')
            ->assertRedirect(route('login'));

        return true;
    });

});
