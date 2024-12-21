<?php

use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UserSeeder};

use function Pest\Laravel\{assertDatabaseHas, seed};

test('Deve conceder permissão ao usuário', function () {

    $user = User::factory()->create();

    $user->givePermission('incluir');

    expect($user->hasPermission('incluir'))->toBeTrue();

    assertDatabaseHas('permissions', [
        'permission' => 'incluir',
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('permission', '=', 'incluir')->first()->id,
    ]);
});

test('permissao deve ter seeder', function () {

    $this->seed(PermissionSeeder::class);

    assertDatabaseHas(
        'permissions',
        [
            'permission' => 'incluir',
        ]
    );
});

test('seeder deve dar permissao ao usuário', function () {

    seed([PermissionSeeder::class, UserSeeder::class]);

    assertDatabaseHas(
        'permissions',
        [
            'permission' => 'incluir',
        ]
    );

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()?->id,
        'permission_id' => Permission::where('permission', '=', 'incluir')->first()?->id,
    ]);
});
