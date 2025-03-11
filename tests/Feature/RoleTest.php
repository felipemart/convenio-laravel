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
        'name' => 'Admin',
    ]);

    assertDatabaseHas('users', [
        'id'      => $user->id,
        'role_id' => Role::where('name', '=', 'Admin')->first()->id,
    ]);
});
test('papeis deve ter seeder', function (): void {
    $this->seed(RoleSeeder::class);

    assertDatabaseHas(
        'roles',
        [
            'name' => 'admin',
        ]
    );
})->skip();

test('seeder deve dar papel ao usuário', function (): void {
    seed([RoleSeeder::class, UserSeeder::class]);

    assertDatabaseHas('users', [
        'id'      => User::first()?->id,
        'role_id' => Role::where('name', '=', 'admin')->first()?->id,
    ]);
})->skip();

test('deve bloquear acesso para usuário sem papel de admin', function (): void {
    $user = User::factory()->withRoles('guest')->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
})->skip();
