<?php

declare(strict_types = 1);

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

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
            'name' => 'Admin',
        ]
    );
    assertDatabaseHas(
        'roles',
        [
            'name' => 'Operadora',
        ]
    );
    assertDatabaseHas(
        'roles',
        [
            'name' => 'Convenio',
        ]
    );
    assertDatabaseHas(
        'roles',
        [
            'name' => 'Conveniada',
        ]
    );
});
