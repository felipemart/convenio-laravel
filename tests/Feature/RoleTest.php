<?php

use App\Models\{Role, User};
use Database\Seeders\{RoleSeeder, UserSeeder};
use Illuminate\Support\Facades\{Cache, DB};

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

test('ter certeza que os papeis estao em cache', function () {
    $user = User::factory()->create();

    $user->giveRole('admin');

    $keyCache = "user::{$user->id}::roles";

    expect(Cache::has($keyCache))->toBeTrue('checando se chave existe')
        ->and(Cache::get($keyCache))->toBe($user->roles);

});

test('checando ser esta  usando cache para papeis', function () {
    $user = User::factory()->create();

    $user->giveRole('admin');

    DB::listen(fn ($query) => throw new Exception('realizou chamada no banco'));

    $user->hasRole('admin');

    expect(true)->toBeTrue();

});
