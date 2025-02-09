<?php

declare(strict_types = 1);

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\seed;

test('deve conceder papel ao usuário', function (): void {
    $user = User::factory()->create();

    $user->giveRole('admin');
    actingAs($user);
    expect($user->hasRole('admin'))->toBeTrue();

    assertDatabaseHas('roles', [
        'name' => 'admin',
    ]);

    assertDatabaseHas('users', [
        'id'      => $user->id,
        'role_id' => Role::where('name', '=', 'admin')->first()->id,
    ]);
});
test('papeis deve ter seeder', function (): void {
    $this->seed(RoleSeeder::class);

    assertDatabaseHas(
        'roles',
        [
            'name' => 'admin', ]
    );
});

test('seeder deve dar papel ao usuário', function (): void {
    seed([RoleSeeder::class, UserSeeder::class]);

    assertDatabaseHas(
        'roles',
        [
            'name' => 'admin',
        ]
    );

    assertDatabaseHas('users', [
        'id'      => User::first()?->id,
        'role_id' => Role::where('name', '=', 'admin')->first()?->id,
    ]);
});

test('deve bloquear acesso para usuário sem papel de admin', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});
