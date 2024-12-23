<?php

use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UserSeeder};
use Illuminate\Support\Facades\DB;

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

test('ter certeza que os permissao estao em cache', function () {
    $user = User::factory()->create();

    $user->givePermission('incluir');

    $keyCache = "user:{$user->id}:permissions";
    expect(Cache::has($keyCache))->toBeTrue('checando se chave existe')
        ->and(Cache::get($keyCache))->toBe($user->permissions);

});
test('checando ser esta  usando cache para permissao', function () {
    $user = User::factory()->create();

    $user->givePermission('incluir');

    DB::listen(fn ($query) => throw new Exception('realizou chamada no banco'));

    $user->hasPermission('incluir');

    expect(true)->toBeTrue();

});
