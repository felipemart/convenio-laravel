<?php

declare(strict_types = 1);

use App\Livewire\Users\Update;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve acessar o perfil do usuario', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('incluir')->create();

    $userEdit = User::factory()->withRoles('admin')->withPermissions('incluir')->create();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->assertSee($userEdit->name)
        ->assertSee($userEdit->email)
        ->assertSee($userEdit->created_at->format('d/m/Y'))
        ->assertOk();
});

test('deve acessar o perfil do usuario deletado deve aparacer o data de exclusao', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('incluir')->create();

    $userEdit = User::factory()->withRoles('admin')->withPermissions('incluir')->create(
        [
            'restored_at' => now(),
        ]
    );

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->assertSee($userEdit->name)
        ->assertSee($userEdit->email)
        ->assertSee($userEdit->role->name)
        ->assertSee($userEdit->created_at->format('d/m/Y'))
        ->assertSee($userEdit->restored_at->format('d/m/Y'))
        ->assertOk();
});

test('deve acessar o perfil do usuario remove a permissao ao usuario', function (): void {
    $admin = User::factory()->withRoles('admin')->create();

    $userEdit       = User::factory()->withRoles('admin')->withPermissions('incluir')->create();
    $permmissionsTb = DB::table('permissions')->first();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->assertSet('setPermissions', [$permmissionsTb->id => true])
        ->assertOk();

    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->set('setPermissions', [$permmissionsTb->id => false])
        ->call('updatePermissions', $permmissionsTb->id)
        ->assertOk();

    $userEdit->refresh();
    expect($userEdit->permissions)->toHaveCount(0);
});

test('deve acessar o perfil do usuario adicionar a permissao ao usuario', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('incluir')->create();

    $userEdit = User::factory()->create();

    $permmissionsTb = DB::table('permissions')->first();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->assertSet('setPermissions', [])
        ->assertOk();

    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->set('setPermissions', [$permmissionsTb->id => true])
        ->call('updatePermissions', $permmissionsTb->id)
        ->assertOk();

    $userEdit->refresh();
    expect($userEdit->permissions)->toHaveCount(1);
});
