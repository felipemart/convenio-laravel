<?php

use App\Livewire\Users\DataUseser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve acessar o perfil do usuario', function () {

    $admin = User::factory()->withRoles('admin')->create();

    $userEdit = User::factory()->withRoles('admin')->withPermissions('incluir')->create();

    actingAs($admin);
    Livewire::test(DataUseser::class, ['id' => $userEdit->id])
        ->assertSee($userEdit->name)
        ->assertSee($userEdit->email)
        ->assertSee($userEdit->created_at)
       ->assertOk();
});

test('deve acessar o perfil do usuario deletado deve aparacer o data de exclusao', function () {

    $admin = User::factory()->withRoles('admin')->withPermissions('incluir')->create();

    $userEdit = User::factory()->withRoles('admin')->withPermissions('incluir')->create(
        [
            'restored_at' => now(),
        ]
    );

    actingAs($admin);
    Livewire::test(DataUseser::class, ['id' => $userEdit->id])
        ->assertSee($userEdit->name)
        ->assertSee($userEdit->email)
        ->assertSee($userEdit->roles[0]->name)
        ->assertSee($userEdit->created_at)
        ->assertSee($userEdit->restored_at)
        ->assertOk();
});

test('deve acessar o perfil do usuario remove a permissao ao usuario', function () {
    $admin = User::factory()->withRoles('admin')->create();

    $userEdit       = User::factory()->withRoles('admin')->withPermissions('incluir')->create();
    $permmissionsTb = DB::table('permissions')->first();

    actingAs($admin);
    Livewire::test(DataUseser::class, ['id' => $userEdit->id])
        ->assertSet('setPermissions', [$permmissionsTb->id => true])
        ->assertOk();

    Livewire::test(DataUseser::class, ['id' => $userEdit->id])
        ->set('setPermissions', [$permmissionsTb->id => false])
        ->call('updatePermissions', $permmissionsTb->id)
        ->assertOk();

    $userEdit->refresh();
    expect($userEdit->permissions)->toHaveCount(0);

});

test('deve acessar o perfil do usuario adicionar a permissao ao usuario', function () {
    $admin = User::factory()->withRoles('admin')->withPermissions('incluir')->create();

    $userEdit = User::factory()->create();

    $permmissionsTb = DB::table('permissions')->first();

    actingAs($admin);
    Livewire::test(DataUseser::class, ['id' => $userEdit->id])
        ->assertSet('setPermissions', [])
        ->assertOk();

    Livewire::test(DataUseser::class, ['id' => $userEdit->id])
        ->set('setPermissions', [$permmissionsTb->id => true])
        ->call('updatePermissions', $permmissionsTb->id)
        ->assertOk();

    $userEdit->refresh();
    expect($userEdit->permissions)->toHaveCount(1);

});
