<?php

declare(strict_types = 1);

use App\Livewire\Users\Index;
use App\Models\Role;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('deve ser acessada somente pelos usaurios papeis', function (): void {
    actingAs(
        User::factory()->withRoles('admin')->create()
    );
    get(route('user.list'))
        ->assertOk();

    actingAs(
        User::factory()->withRoles('empresas')->create()
    );

    get(route('user.list'))
        ->assertOk();
});

test('nao pode ser acessada pelo que nao tem permissao', function (): void {
    actingAs(
        User::factory()->create()
    );
    get(route('user.list'))
        ->assertForbidden();
});

test('composente deve carregar todos os usuarios', function (): void {
    $users = User::factory()->withRoles('test')->count(10)->create();

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

test('vefiricando ser a table tem formato', function (): void {
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'empresa', 'label' => 'Empresa', 'sortable' => false],
            ['key' => 'roles', 'label' => 'Nivel', 'sortable' => false],
        ]);
});

test('deve filtar os usuarios por nome e email', function (): void {
    $admin = User::factory()->withRoles('admin')->create([
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

test('deve filtar os usuarios pelo nivel', function (): void {
    $admin = User::factory()->withRoles('admin')->create([
        'name'  => 'Admin',
        'email' => 'admin@gamail.com',
    ]);
    $mario = User::factory()->withRoles('test')->create([
        'name'  => 'Mario',
        'email' => 'mario@gamail.com',
    ]);

    $roles  = Role::where('name', '=', 'admin')->first();
    $roles2 = Role::where('name', '=', 'test')->first();

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

test('deve filtar os usuarios deletado', function (): void {
    $admin = User::factory()->withRoles('admin')->create([
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

test('deve ordenar os usuarios', function (): void {
    $admin = User::factory()->withRoles('admin')->create([
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

test('paginacao dos resultados', function (): void {
    $admin = User::factory()->withRoles('admin')->create([
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
