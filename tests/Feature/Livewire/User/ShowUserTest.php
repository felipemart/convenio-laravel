<?php

declare(strict_types = 1);

use App\Livewire\User\Index;
use App\Livewire\User\Show;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve ser capaz de ver o perfil do usuario', function (): void {
    $admin = User::factory()->withRoles('admin')->create();
    $user  = User::factory()->withRoles('test')->create();

    actingAs($admin);

    Livewire::test(Show::class)
        ->call('loadUser', $user->id)
        ->assertSet('user.id', $user->id)
        ->assertSet('modal', true)
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->created_at->format('d/m/Y H:i'))
        ->assertDontSee($user->deleted_at);
});

test('deve ser capaz de ver o perfil do usuario deletado', function (): void {
    $admin = User::factory()->withRoles('admin')->create();
    $user  = User::factory()->withRoles('test')->create([
        'deleted_at' => now(),
        'deleted_by' => $admin->id,
    ]);

    actingAs($admin);

    Livewire::test(Show::class)
        ->call('loadUser', $user->id)
        ->assertSet('user.id', $user->id)
        ->assertSet('modal', true)
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->created_at->format('d/m/Y H:i'))
        ->assertSee($user->deleted_at->format('d/m/Y H:i'))
        ->assertSee($user->deletedBy->name);
});

test('deve ser capaz de abrir o modal por evendo', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('usuario.list')->create();
    $user  = User::factory()->withRoles('test')->create();

    actingAs($admin);

    $lwShow = Livewire::test(Show::class)
        ->assertSet('user', null)
        ->assertSet('modal', false);

    Livewire::test(Index::class)
        ->call('show', $user->id)
        ->assertDispatched('user.showing', userId: $user->id);
});

test('loadUser tenha o atributo on livewire ', function (): void {
    $livewireClass = new Show();
    $reflection    = new ReflectionClass($livewireClass);
    $atributes     = $reflection->getMethod('loadUser')->getAttributes();
    expect($atributes)->toHaveCount(1);
    /** @var @var ReflectionAttribute $atribute */
    $atribute = $atributes[0];

    expect($atribute->getName())->toBe(On::class)
        ->and($atribute->getArguments())->toHaveCount(1)
        ->and($atribute->getArguments()[0])->toBe('user.showing');
});
