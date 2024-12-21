<?php

use App\Models\{Role, User};
use Database\Seeders\{RoleSeeder, UserSeeder};

use function Pest\Laravel\{actingAs, assertDatabaseHas, seed};

test('deve conceder papel ao usuário', function () {

    $user = User::factory()->create();

    $user->giveRole('admin');

    expect($user->hasRole('admin'))->toBeTrue();

    assertDatabaseHas('roles', [
        'role' => 'admin',
    ]);

    assertDatabaseHas('role_user', [
        'user_id' => $user->id,
        'role_id' => Role::where('role', '=', 'admin')->first()->id,
    ]);
});
test('papeis deve ter seeder', function () {

    $this->seed(RoleSeeder::class);

    assertDatabaseHas(
        'roles',
        [
            'role' => 'admin', ]
    );
});

test('seeder deve dar papel ao usuário', function () {

    seed([RoleSeeder::class, UserSeeder::class]);

    assertDatabaseHas(
        'permissions',
        [
            'permission' => 'incluir',
        ]
    );

    assertDatabaseHas('role_user', [
        'user_id' => User::first()?->id,
        'role_id' => Role::where('role', '=', 'admin')->first()?->id,
    ]);
});

test('deve bloquear acesso para usuário sem papel de admin', function () {

    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

});
