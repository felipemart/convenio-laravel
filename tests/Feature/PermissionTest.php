<?php

use App\Models\{Permission, User};

use function Pest\Laravel\assertDatabaseHas;

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
