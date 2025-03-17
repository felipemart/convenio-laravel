<?php

declare(strict_types = 1);

use App\Livewire\User\Update;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve acessar o perfil do usuario', function (): void {
    $admin = User::factory()->withRoles('admin')->create();

    $userEdit = User::factory()->withRoles('admin')->create();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->assertSee($userEdit->name)
        ->assertSee($userEdit->email)
        ->assertSee($userEdit->created_at->format('d/m/Y'))
        ->assertOk();
});

test('deve acessar o perfil do usuario deletado deve aparacer o data de exclusao', function (): void {
    $admin = User::factory()->withRoles('admin')->create();

    $userEdit = User::factory()->withRoles('admin')->create(
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
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->withRoles('admin')->create();

    $permmissionsTb = DB::table('permissions')->first();

    $userEdit = User::factory()->withRoles('admin')->withPermissions($permmissionsTb->permission)->create();

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
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->withRoles('admin')->create();

    $userEdit = User::factory()->withRoles('admin')->create();

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

test('deve acessar o perfil do usuario adicionar so deve ver permissoa do nivel do usuario', function (): void {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->withRoles('admin')->create();

    $userEdit = User::factory()->withRoles('guest')->create();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $admin->id])
        ->assertSee('operadora.create')
        ->assertOk();

    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->assertDontSee('operadora.create')
        ->assertOk();
});
