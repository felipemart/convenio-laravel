<?php

use App\Models\{Role, User};

use function Pest\Laravel\assertDatabaseHas;

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
