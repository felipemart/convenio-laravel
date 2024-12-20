<?php

use App\Models\{Permission, User};

use function Pest\Laravel\assertDatabaseHas;

test('deve conceder permissão ao usuário', function () {

    $user = User::factory()->create();

    $user->givePermission('admin');

    expect($user->hasPermission('admin'))->toBeTrue();

    assertDatabaseHas('permissions', [
        'permission' => 'admin',
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('permission', '=', 'admin')->first()->id,
    ]);
});
