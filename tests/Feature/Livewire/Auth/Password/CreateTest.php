<?php

declare(strict_types = 1);

use App\Livewire\Auth\Password;

use App\Livewire\User\Create;
use App\Models\Empresa;
use App\Models\Role;
use App\Models\User;

use App\Notifications\EmailCriacaoSenha;

use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertTrue;

test('deve acessar a tela de criar senha', function (): void {
    Notification::fake();
    $admin = User::factory()->withRoles('admin')->create();

    actingAs($admin);
    Livewire::test(Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('roleSelect', 1) // Assuming role ID 1 exists
        ->set('empresaSelect', 1) // Assuming empresa ID 1 exists
        ->call('save')
        ->assertHasNoErrors();

    $user = User::where('email', 'johndoe@example.com')->first();

    Notification::assertSentTo(
        $user,
        EmailCriacaoSenha::class,
        function (EmailCriacaoSenha $notification) use ($user): true {
            Livewire::test(Password\Create::class, ['token' => $notification->token, 'email' => $user->email])
                ->set('password', 'new-password')
                ->set('password_confirmation', 'new-password')
                ->call('criarSenha')
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

test('deve acessar validar o token', function (): void {
    Notification::fake();
    $admin = User::factory()->withRoles('admin')->create();

    actingAs($admin);
    Livewire::test(Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('roleSelect', 1) // Assuming role ID 1 exists
        ->set('empresaSelect', 1) // Assuming empresa ID 1 exists
        ->call('save')
        ->assertHasNoErrors();

    $user = User::where('email', 'johndoe@example.com')->first();

    Notification::assertSentTo(
        $user,
        EmailCriacaoSenha::class,
        function (EmailCriacaoSenha $notification) use ($user): true {
            Livewire::test(Password\Create::class, ['token' => $notification->token . 'test', 'email' => $user->email])
                ->assertSessionHas('status', 'Token inválido.')
                ->assertRedirect(route('login'));

            return true;
        }
    );
});

test('sets session flash message for password reset error', function (): void {
    Notification::fake();
    $admin = User::factory()->withRoles('admin')->create();

    actingAs($admin);
    Livewire::test(Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('roleSelect', 1) // Assuming role ID 1 exists
        ->set('empresaSelect', 1) // Assuming empresa ID 1 exists
        ->call('save')
        ->assertHasNoErrors();

    $user = User::where('email', 'johndoe@example.com')->first();

    Notification::assertSentTo(
        $user,
        EmailCriacaoSenha::class,
        function (EmailCriacaoSenha $notification) use ($user): true {
            Illuminate\Support\Facades\Password::shouldReceive('reset')
                ->andReturn(Illuminate\Support\Facades\Password::INVALID_TOKEN);

            Livewire::test(Password\Create::class, ['token' => $notification->token, 'email' => $user->email])
                ->set('password', 'newpassword')
                ->set('password_confirmation', 'newpassword')
                ->call('criarSenha')
                ->assertSee('Ocorreu um erro ao criar a senha.');

            return true;
        }
    );
});

it('sets session flash message for invalid user', function (): void {
    Notification::fake();
    $admin = User::factory()->withRoles('admin')->create();

    actingAs($admin);
    Livewire::test(Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'johndoe@example.com')
        ->set('roleSelect', 1) // Assuming role ID 1 exists
        ->set('empresaSelect', 1) // Assuming empresa ID 1 exists
        ->call('save')
        ->assertHasNoErrors();

    $user = User::where('email', 'johndoe@example.com')->first();

    Notification::assertSentTo(
        $user,
        EmailCriacaoSenha::class,
        function (EmailCriacaoSenha $notification) use ($user): true {
            Illuminate\Support\Facades\Password::shouldReceive('reset')
                ->andReturn(Illuminate\Support\Facades\Password::INVALID_USER);

            Livewire::test(Password\Create::class, ['token' => $notification->token, 'email' => $user->email])
                ->set('password', 'newpassword')
                ->set('password_confirmation', 'newpassword')
                ->call('criarSenha')
                ->assertSee('Não conseguimos encontrar um usuário com esse endereço de e-mail.');

            return true;
        }
    );
});

test('changeEmpresa updates empresa based on roleSelect', function (): void {
    seed([RoleSeeder::class, PermissionSeeder::class]);
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    actingAs($admin);

    $component = Livewire::test(Create::class)
        ->set('roleSelect', 2)
        ->call('changeEmpresa');

    $component->assertSet('empresa', function ($empresas) use ($empresa): true {
        foreach ($empresa->toArray() as $key => $value) {
            expect($value)->toBe($empresas[0][$key]);
        }

        return true;
    });
});
