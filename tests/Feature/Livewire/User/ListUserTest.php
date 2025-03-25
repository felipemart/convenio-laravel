<?php

declare(strict_types = 1);

use App\Livewire\User\Index;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

test('should be accessible only by users with roles', function (): void {
    seed(PermissionSeeder::class);
    actingAs(
        User::factory()->withRoles('admin')->withPermissions('usuario.list')->create()
    );
    get(route('user.list'))
        ->assertOk();

    actingAs(
        User::factory()->withRoles('operadora')->withPermissions('usuario.list')->create()
    );

    get(route('user.list'))
        ->assertOk();
});
test('should not be accessible by users without permission', function (): void {
    actingAs(
        User::factory()->withRoles('guest')->create()
    );
    get(route('user.list'))
        ->assertForbidden();
});
test('component should load all users', function (): void {
    actingAs(
        User::factory()->withRoles('admin')->withPermissions('usuario.list')->create()
    );
    $users = User::factory()->withRoles('test')->count(9)->create();

    $lw = Livewire::test(Index::class);
    $lw->assertSet('users', function ($users): true {
        expect($users)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($users as $user) {
        $lw->assertSee($user->name);
    }
});
test('verifying if the table has the correct format', function (): void {
    actingAs(
        User::factory()->withRoles('admin')->withPermissions('usuario.list')->create()
    );
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'empresa', 'label' => 'Empresa', 'sortable' => false],
            ['key' => 'roles', 'label' => 'Nivel', 'sortable' => false],
        ]);
});
test('should filter users by name and email', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('usuario.list')->create([
        'name'  => 'Admin',
        'email' => 'admin@gamail.com',
    ]);
    $mario = User::factory()->withRoles('admin')->create([
        'name'  => 'Mario',
        'email' => 'mario@gamail.com',
    ]);

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('users', function ($users): true {
            expect($users)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', 'mar')
        ->assertSet('users', function ($users): true {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Mario');

            return true;
        });
});
test('should filter users by role', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('usuario.list')->create([
        'name'  => 'Admin',
        'email' => 'admin@gamail.com',
    ]);
    $mario = User::factory()->withRoles('test')->create([
        'name'  => 'Mario',
        'email' => 'mario@gamail.com',
    ]);

    $roles  = Role::where('name', '=', 'Admin')->first();
    $roles2 = Role::where('name', '=', 'Test')->first();

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('users', function ($users): true {
            expect($users)
                ->toHaveCount(2);

            return true;
        })
        ->set('searchRole', [$roles->id])
        ->assertSet('users', function ($users): true {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Admin');

            return true;
        })
        ->set('searchRole', [$roles->id, $roles2->id])
        ->assertSet('users', function ($users): true {
            expect($users)
                ->toHaveCount(2);

            return true;
        });
});
test('should filter deleted users', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('usuario.list')->create([
        'name'  => 'Admin',
        'email' => 'admin@gamail.com',
    ]);
    $deleteUser = User::factory()->withRoles('test')->count(2)->create(['deleted_at' => now()]);

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('users', function ($users): true {
            expect($users)
                ->toHaveCount(1);

            return true;
        })
        ->set('search_trash', true)
        ->assertSet('users', function ($users): true {
            expect($users)
                ->toHaveCount(2);

            return true;
        });
});
test('should sort users', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('usuario.list')->create([
        'name'  => 'Admin',
        'email' => 'admin@gamail.com',
    ]);
    $mario = User::factory()->withRoles('test')->create([
        'name'  => 'Mario',
        'email' => 'mario@gamail.com',
    ]);
    actingAs($admin);
    Livewire::test(Index::class)
        ->set('sortBy', ['column' => 'name', 'direction' => 'asc'])
        ->assertSet('users', function ($users): true {
            expect($users)
                ->first()->name->toBe('Admin')
                ->and($users)->last()->name->toBe('Mario');

            return true;
        })
        ->set('sortBy', ['column' => 'name', 'direction' => 'desc'])
        ->assertSet('users', function ($users): true {
            expect($users)
                ->first()->name->toBe('Mario')
                ->and($users)->last()->name->toBe('Admin');

            return true;
        });
});
test('pagination of results', function (): void {
    $admin = User::factory()->withRoles('admin')->withPermissions('usuario.list')->create([
        'name'  => 'Admin',
        'email' => 'admin@gamail.com',
    ]);
    User::factory()->withRoles('test')->count(50)->create();

    actingAs($admin);
    Livewire::test(Index::class)
        ->set('perPage', 15)
        ->assertSet('users', function (LengthAwarePaginator $users): true {
            expect($users)
                ->toHaveCount(15);

            return true;
        });
});
