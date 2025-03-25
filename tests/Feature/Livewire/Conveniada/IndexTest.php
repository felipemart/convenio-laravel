<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Index;
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
            ->withPermissions('conveniada.list')
            ->create()
    );

    get(route('conveniada.list'))
        ->assertOk();

    actingAs(
        User::factory()
            ->withRoles('operadora')
            ->withPermissions('conveniada.list')
            ->create()
    );

    get(route('conveniada.list'))
        ->assertOk();

    actingAs(
        User::factory()
            ->withRoles('convenio')
            ->withPermissions('conveniada.list')
            ->updateEmpresa($emprsa->id)
            ->create()
    );

    get(route('conveniada.list'))
        ->assertOk();
});

test('cannot be accessed by users without permission', function (): void {
    $this->seed(RoleSeeder::class);
    actingAs(
        User::factory()->withRoles('guest')->create()
    );
    get(route('conveniada.list'))
        ->assertForbidden();
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsa->giveConvenio($emprsa->id);
    actingAs(
        User::factory()->withRoles('conveniada')->create()
    );
    get(route('conveniada.list'))
        ->assertForbidden();
});

test('component should load all users', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);
    $conveniadas = [];

    for ($i = 0; $i < 10; $i++) {
        $empresaConveniada = Empresa::factory()->create();
        $empresaConveniada->giveConveniada($emprsaConvenio->id);
        $conveniadas[] = $empresaConveniada;
    }

    actingAs(
        User::factory()
            ->withRoles('convenio')
            ->withPermissions('conveniada.list')
            ->updateEmpresa($emprsaConvenio->id)
            ->create()
    );

    $lw = Livewire::test(Index::class);
    $lw->assertSet('conveniadas', function ($conveniadas): true {
        expect($conveniadas)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($conveniadas as $conveniada) {
        $lw->assertSee($conveniada->nome_fantasia)
            ->assertSee($conveniada->razao_social);
    }
});

test('verifying if the table has the correct format', function (): void {
    actingAs(
        User::factory()
            ->withRoles('admin')
            ->withPermissions('conveniada.list')
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
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);
    $conveniadas = [];

    for ($i = 0; $i < 2; $i++) {
        $empresaConveniada = Empresa::factory()->create();
        $empresaConveniada->giveConveniada($emprsaConvenio->id);
        $conveniadas[] = $empresaConveniada;
    }

    actingAs(
        User::factory()
            ->withRoles('convenio')
            ->withPermissions('conveniada.list')
            ->updateEmpresa($emprsaConvenio->id)
            ->create()
    );

    Livewire::test(Index::class)
        ->assertSet('conveniadas', function ($conveniadas): true {
            expect($conveniadas)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', $conveniadas[0]->cnpj)
        ->assertSet('conveniadas', function ($conveniadas): true {
            expect($conveniadas)
                ->toHaveCount(1);

            return true;
        });
});

test('should filter deleted users', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);

    for ($i = 0; $i <= 2; $i++) {
        $empresaConveniada = Empresa::factory()->create();
        $empresaConveniada->giveConveniada($emprsaConvenio->id);

        if ($i > 0) {
            $empresaConveniada->conveniadas()->first()->delete();
        }
    }

    actingAs(
        User::factory()
            ->withRoles('convenio')
            ->withPermissions('conveniada.list')
            ->updateEmpresa($emprsaConvenio->id)
            ->create()
    );

    Livewire::test(Index::class)
        ->assertSet('conveniadas', function ($conveniadas): true {
            expect($conveniadas)
                ->toHaveCount(1);

            return true;
        })
        ->set('search_trash', true)
        ->assertSet('conveniadas', function ($conveniadas): true {
            expect($conveniadas)
                ->toHaveCount(2);

            return true;
        });
});

test('pagination of results', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsaConvenio = Empresa::factory()->create();
    $emprsaConvenio->giveConvenio($emprsa->id);

    for ($i = 0; $i < 20; $i++) {
        $empresaConveniada = Empresa::factory()->create();
        $empresaConveniada->giveConveniada($emprsaConvenio->id);
    }

    actingAs(
        User::factory()
            ->withRoles('convenio')
            ->withPermissions('conveniada.list')
            ->updateEmpresa($emprsaConvenio->id)
            ->create()
    );

    Livewire::test(Index::class)
        ->set('perPage', 15)
        ->assertSet('conveniadas', function (LengthAwarePaginator $conveniadas): true {
            expect($conveniadas)
                ->toHaveCount(15);

            return true;
        });
});
