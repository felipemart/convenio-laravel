<?php

declare(strict_types = 1);

use App\Livewire\User\PermissionUser;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

test('should be accessible only by users with roles', function (): void {
    actingAs(
        User::factory()->withRoles('admin')->create()
    );
    get(route('user.permissions', ['id' => 1]))
        ->assertOk();
});

test('verifying if the table has the correct format', function (): void {
    actingAs(
        User::factory()->withRoles('admin')->create()
    );
    Livewire::test(PermissionUser::class, ['id' => 1])
        ->assertSet('headers', [
            [
                'key'   => 'descricao',
                'label' => 'Permissão',
            ],
        ]);
});

test('should render the correct view', function () {
    actingAs(
        User::factory()->withRoles('admin')->create()
    );
    Livewire::test(PermissionUser::class, ['id' => 1])
        ->assertViewIs('livewire.user.permission');
});

test('should update setPermissions correctly', function () {
    $user = User::factory()->withRoles('admin')->create();
    actingAs($user);

    Livewire::test(PermissionUser::class, ['id' => $user->id])
        ->call('updateSetPermissions')
        ->assertSet('setPermissions', function ($setPermissions) use ($user) {
            foreach ($user->permissions as $permission) {
                if (! isset($setPermissions[$permission->id]) || ! $setPermissions[$permission->id]) {
                    return false;
                }
            }

            return true;
        });
});

test('should update permissions correctly', function () {
    seed(PermissionSeeder::class);
    $user       = User::factory()->withRoles('admin')->create();
    $permission = App\Models\Permission::first();

    actingAs($user);

    Livewire::test(PermissionUser::class, ['id' => $user->id])
        ->set('setPermissions', [$permission->id => true])
        ->call('updatePermissions', $permission->id)
        ->assertHasNoErrors();

    $this->assertTrue($user->permissions->contains($permission));

    Livewire::test(PermissionUser::class, ['id' => $user->id])
        ->set('setPermissions', [$permission->id => false])
        ->call('updatePermissions', $permission->id)
        ->assertHasNoErrors();
    $user->refresh();
    $this->assertFalse($user->permissions->contains($permission));
});
