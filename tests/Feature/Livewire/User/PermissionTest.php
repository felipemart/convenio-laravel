<?php

declare(strict_types = 1);

use App\Models\Permission;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\seed;

test('Deve conceder permissão ao usuário', function (): void {
    $user = User::factory()->withRoles('admin')->create();

    $user->givePermission('operadora.create');

    expect($user->hasPermission('operadora.create'))->toBeTrue();

    assertDatabaseHas('permissions', [
        'permission' => 'operadora.create',
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::where('permission', '=', 'operadora.create')->first()->id,
    ]);
});

test('permissao deve ter seeder', function (): void {
    $this->seed(PermissionSeeder::class);

    assertDatabaseHas(
        'permissions',
        [
            'permission' => 'operadora.create',
        ]
    );
});

test('seeder deve dar permissao ao usuário', function (): void {
    seed([PermissionSeeder::class, UserSeeder::class]);

    assertDatabaseHas(
        'permissions',
        [
            'permission' => 'operadora.create',
        ]
    );

    assertDatabaseHas('permission_user', [
        'user_id'       => User::first()?->id,
        'permission_id' => Permission::where('permission', '=', 'operadora.create')->first()?->id,
    ]);
});

test('ter certeza que os permissao estao em cache', function (): void {
    $user = User::factory()->withRoles('admin')->create();

    $user->givePermission('operadora.create');

    $keyCache = "user:" . $user->id . ".permissions";

    expect(Session::has($keyCache))->toBeTrue('checando se chave existe')
        ->and(Session::get($keyCache))->toBe($user->permissions);
});

test('checando ser esta  usando cache para permissao', function (): void {
    $user = User::factory()->withRoles('admin')->create();

    $user->givePermission('operadora.create');

    DB::listen(fn ($query) => throw new Exception('realizou chamada no banco'));

    $user->hasPermission('operadora.create');

    expect(true)->toBeTrue();
});
