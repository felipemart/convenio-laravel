<?php

use App\Livewire\Users\Index;
use App\Models\{Role, User};
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs, get};

test('deve ser acessada somente pelos usaurios papeis', function () {

    actingAs(
        User::factory()->withRoles('admin')->create()
    );
    get(route('user.list'))
        ->assertOk();

    actingAs(
        User::factory()->withRoles('operadora')->create()
    );

    get(route('user.list'))
        ->assertOk();
});

test('nao pode ser acessada pelo que nao tem permissao', function () {
    actingAs(
        User::factory()->create()
    );
    get(route('user.list'))
        ->assertForbidden();

});

test('composente deve carregar todos os usuarios', function () {

    $users = User::factory()->count(10)->create();

    $lw = Livewire::test(Index::class);
    $lw->assertSet('users', function ($users) {
        expect($users)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($users as $user) {
        $lw->assertSee($user->name);
    }

});

test('vefiricando ser a table tem formato', function () {
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'roles', 'label' => 'Nivel'],
        ]);
});

test('deve filtar os usuarios por nome e email', function () {
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
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', 'mar')
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Mario');

            return true;
        });
});

test('deve filtar os usuarios pelo nivel', function () {
    $admin = User::factory()->withRoles('admin')->create([
        'name'  => 'Admin',
        'email' => 'admin@gamail.com',
    ]);
    $mario = User::factory()->create([
        'name'  => 'Mario',
        'email' => 'mario@gamail.com',
    ]);

    $roles = Role::where('role', '=', 'admin')->first();

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(2);

            return true;
        })
        ->set('search_role', [$roles->id])
        ->assertSet('users', function ($users) {
            expect($users)
                ->toHaveCount(1)
                ->first()->name->toBe('Admin');

            return true;
        });
});
