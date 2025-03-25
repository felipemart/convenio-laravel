<?php

declare(strict_types = 1);

use App\Livewire\Convenio\Index;
use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('should only be accessed by users with roles', function (): void {
    $this->seed([RoleSeeder::class, PermissionSeeder::class]);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsa->giveConvenio($emprsa->id);

    actingAs(
        User::factory()
            ->withRoles('admin')
            ->withPermissions('convenio.list')
            ->create()
    );

    get(route('convenio.list'))
        ->assertOk();

    actingAs(
        User::factory()
            ->withRoles('operadora')
            ->withPermissions('convenio.list')
            ->create()
    );

    get(route('convenio.list'))
        ->assertOk();
});

test('cannot be accessed by users without permission', function (): void {
    $this->seed([RoleSeeder::class, PermissionSeeder::class]);
    actingAs(
        User::factory()->withRoles('guest')->create()
    );
    get(route('convenio.list'))
        ->assertForbidden();

    actingAs(
        User::factory()
            ->withRoles('admin')
            ->create()
    );
    get(route('convenio.list'))
        ->assertRedirect(route('dashboard'));

    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsa->giveConvenio($emprsa->id);
    actingAs(
        User::factory()->withRoles('convenio')->create()
    );
    get(route('convenio.list'))
        ->assertForbidden();
});

test('component should load all users', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $convenios = [];

    for ($i = 0; $i < 10; $i++) {
        $emprsaConvenio = Empresa::factory()->create();
        $emprsaConvenio->giveConvenio($emprsa->id);
        $convenios[] = $emprsaConvenio;
    }

    actingAs(
        User::factory()
            ->withRoles('operadora')
            ->withPermissions('convenio.list')
            ->updateEmpresa($emprsa->id)
            ->create()
    );

    $lw = Livewire::test(Index::class);
    $lw->assertSet('convenios', function ($convenios): true {
        expect($convenios)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($convenios as $convenio) {
        $lw->assertSee($convenio->nome_fantasia)
            ->assertSee($convenio->razao_social);
    }
});

test('verifying if the table has the correct format', function (): void {
    actingAs(
        User::factory()
            ->withRoles('admin')
            ->withPermissions('convenio.list')
            ->create()
    );
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'razao_social', 'label' => 'Razão Social'],
            ['key' => 'nome_fantasia', 'label' => 'Nome Fantasia'],
        ]);
});

test('should filter users by name and email', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $convenios = [];

    for ($i = 0; $i < 2; $i++) {
        $emprsaConvenio = Empresa::factory()->create();
        $emprsaConvenio->giveConvenio($emprsa->id);
        $convenios[] = $emprsaConvenio;
    }

    actingAs(
        User::factory()
            ->withRoles('operadora')
            ->withPermissions('convenio.list')
            ->updateEmpresa($emprsa->id)->create()
    );

    Livewire::test(Index::class)
        ->assertSet('convenios', function ($convenios): true {
            expect($convenios)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', $convenios[0]->cnpj)
        ->assertSet('convenios', function ($convenios): true {
            expect($convenios)
                ->toHaveCount(1);

            return true;
        });
});

test('should filter deleted users', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();

    for ($i = 0; $i <= 2; $i++) {
        $emprsaConvenio = Empresa::factory()->create();
        $emprsaConvenio->giveConvenio($emprsa->id);

        if ($i > 0) {
            Convenio::where('empresa_id', '=', $emprsaConvenio->id)->delete();
        }
    }

    actingAs(
        User::factory()
            ->withRoles('operadora')
            ->withPermissions('convenio.list')
            ->updateEmpresa($emprsa->id)->create()
    );

    Livewire::test(Index::class)
        ->assertSet('convenios', function ($convenio): true {
            expect($convenio)
                ->toHaveCount(1);

            return true;
        })
        ->set('search_trash', true)
        ->assertSet('convenios', function ($convenio): true {
            expect($convenio)
                ->toHaveCount(2);

            return true;
        });
});

test('pagination of results', function (): void {
    $this->seed([RoleSeeder::class, PermissionSeeder::class]);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();

    for ($i = 0; $i < 20; $i++) {
        $emprsaConvenio = Empresa::factory()->create();
        $emprsaConvenio->giveConvenio($emprsa->id);
    }

    actingAs(
        User::factory()->withRoles('operadora')->withPermissions('convenio.list')->updateEmpresa($emprsa->id)->create()
    );

    Livewire::test(Index::class)
        ->set('perPage', 15)
        ->assertSet('convenios', function (LengthAwarePaginator $convenios): true {
            expect($convenios)
                ->toHaveCount(15);

            return true;
        });
});
