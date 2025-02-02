<?php

declare(strict_types = 1);

use App\Livewire\Auth\Password\recovery;
use App\Livewire\Auth\Password\Reset;
use App\Models\User;
use App\Notifications\EmailRecuperacaoSenha;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;
use function PHPUnit\Framework\assertTrue;

test('precisa receber um token validos', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(recovery::class)
        ->set('email', $user->email)
        ->call('recuperacaoSenha');

    Notification::assertSentTo($user, EmailRecuperacaoSenha::class, function (EmailRecuperacaoSenha $notification) {
    get(route('password.reset') . '?token=' . $notification->token)
    ->assertSuccessful();

        get(route('password.reset') . '?token=invalido-token')
            ->assertRedirect(route('login'));

        return true;
        });
});

test('teste possivel de resetar a senha', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(recovery::class)
        ->set('email', $user->email)
        ->call('recuperacaoSenha');

    Notification::assertSentTo(
        $user,
        EmailRecuperacaoSenha::class,
        function (EmailRecuperacaoSenha $notification) use ($user) {
            Livewire::test(Reset::class, ['token' => $notification->token, 'email' => $user->email])
                ->set('password', 'new-password')
                ->set('password_confirmation', 'new-password')
                ->call('resetarSenha')
                ->assertHasNoErrors()
                ->assertRedirect(route('login'));

            $user->refresh();

            assertTrue(
                Hash::check('new-password', $user->password)
            );

            return true;
        }
    );
});
test('certificando-se de que o e-mail é real', function ($f) {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(recovery::class)
        ->set('email', $user->email)
        ->call('recuperacaoSenha');

    Notification::assertSentTo(
        $user,
        EmailRecuperacaoSenha::class,
        function (EmailRecuperacaoSenha $notification) use ($user, $f) {
            Livewire::test(Reset::class, ['token' => $notification->token, 'email' => $user->email])
                ->set($f->field, $f->value)
                ->call('resetarSenha')
                ->assertHasErrors([$f->field => $f->rule]);
            ;

            return true;
        }
    );
})->with([
    'email::required'     => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::email'        => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'password::required'  => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password::confirmed' => (object)['field' => 'password', 'value' => 'password-not-confirmed', 'rule' => 'confirmed'],
]);

test('certificar que funcao obfuscar_email ', function () {
    $email         = 'johndoe@example.com';
    $obfuscarEmail = obfuscar_email($email);
    expect($obfuscarEmail)->toBe('jo*****@example.com');

    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(recovery::class)
        ->set('email', $user->email)
        ->call('recuperacaoSenha');

    Notification::assertSentTo(
        $user,
        EmailRecuperacaoSenha::class,
        function (EmailRecuperacaoSenha $notification) use ($user) {
            Livewire::test(Reset::class, ['token' => $notification->token, 'email' => $user->email])
                ->assertSet('obfuscarEmail', obfuscar_email($user->email));

            return true;
        }
    );
});
