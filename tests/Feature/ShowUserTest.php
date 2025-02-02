<?php

declare(strict_types = 1);

use App\Livewire\Users\Index;
use App\Livewire\Users\Show;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve ser capaz de ver o perfil do usuario', function () {
    $admin = User::factory()->withRoles('admin')->withPermissions('incluir')->create();
    $user  = User::factory()->withRoles('test')->withPermissions('incluir')->create();

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

test('deve ser capaz de ver o perfil do usuario deletado', function () {
    $admin = User::factory()->withRoles('admin')->withPermissions('incluir')->create();
    $user  = User::factory()->withRoles('test')->withPermissions('incluir')->create([
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

test('deve ser capaz de abrir o modal por evendo', function () {
    $admin = User::factory()->withRoles('admin')->withPermissions('incluir')->create();
    $user  = User::factory()->withRoles('test')->withPermissions('incluir')->create();

    actingAs($admin);

    $lwShow = Livewire::test(Show::class)
        ->assertSet('user', null)
        ->assertSet('modal', false);

    Livewire::test(Index::class)
        ->call('show', $user->id)
        ->assertDispatched('user.showing', userId: $user->id);
});

test('loadUser tenha o atributo on livewire ', function () {
    $livewireClass = new Show();
    $reflection    = new ReflectionClass($livewireClass);
    $atributes     = $reflection->getMethod('loadUser')->getAttributes();
    expect($atributes)->toHaveCount(1);
    /** @var @var ReflectionAttribute $atribute */
    $atribute = $atributes[0];

    expect($atribute->getName())->toBe('Livewire\Attributes\On')
        ->and($atribute->getArguments())->toHaveCount(1)
        ->and($atribute->getArguments()[0])->toBe('user.showing');
});
