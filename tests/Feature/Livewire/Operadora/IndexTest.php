<?php

declare(strict_types = 1);

use App\Livewire\Operadora\Index;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('should only be accessed by users with roles', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();

    actingAs(
        User::factory()->withRoles('admin')->withPermissions('operadora.list')->create()
    );

    get(route('operadora.list'))
        ->assertOk();
});

test('cannot be accessed by users without permission', function (): void {
    $this->seed(RoleSeeder::class);
    actingAs(
        User::factory()->withRoles('guest')->create()
    );
    get(route('operadora.list'))
        ->assertForbidden();
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsa->giveConvenio($emprsa->id);
    actingAs(
        User::factory()->withRoles('operadora')->create()
    );
    get(route('operadora.list'))
        ->assertForbidden();
});

test('component should load all users', function (): void {
    $this->seed(RoleSeeder::class);
    $operadoras = [];

    for ($i = 0; $i < 10; $i++) {
        $empresa = Empresa::factory()->create();
        $empresa->giveOperadora();
        $operadoras[] = $empresa;
    }

    actingAs(
        User::factory()->withRoles('admin')->withPermissions('operadora.list')->create()
    );

    $lw = Livewire::test(Index::class);
    $lw->assertSet('operadoras', function ($operadoras): true {
        expect($operadoras)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($operadoras as $operadora) {
        $lw->assertSee($operadora->nome_fantasia)
            ->assertSee($operadora->razao_social);
    }
});

test('verifying if the table has the correct format', function (): void {
    actingAs(
        User::factory()->withRoles('admin')->withPermissions('operadora.list')->create()
    );
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'razao_social', 'label' => 'Razão Social'],
            ['key' => 'nome_fantasia', 'label' => 'Nome Fantasia'],
        ]);
});

test('should filter users by CNPJ', function (): void {
    $this->seed(RoleSeeder::class);
    $operadoras = [];

    for ($i = 0; $i < 10; $i++) {
        $empresa = Empresa::factory()->create();
        $empresa->giveOperadora();
        $operadoras[] = $empresa;
    }

    actingAs(
        User::factory()->withRoles('admin')->withPermissions('operadora.list')->create()
    );

    Livewire::test(Index::class)
        ->assertSet('operadoras', function ($operadoras): true {
            expect($operadoras)
                ->toHaveCount(10);

            return true;
        })
        ->set('search', $operadoras[0]->cnpj)
        ->assertSet('operadoras', function ($operadoras): true {
            expect($operadoras)
                ->toHaveCount(1);

            return true;
        });
});

test('should filter deleted users', function (): void {
    $this->seed(RoleSeeder::class);

    for ($i = 0; $i <= 2; $i++) {
        $empresa = Empresa::factory()->create();
        $empresa->giveOperadora();

        if ($i > 0) {
            $empresa->operadora()->first()->delete();
        }
    }

    actingAs(
        User::factory()->withRoles('admin')->withPermissions('operadora.list')->create()
    );

    Livewire::test(Index::class)
        ->assertSet('operadoras', function ($operadoras): true {
            expect($operadoras)
                ->toHaveCount(1);

            return true;
        })
        ->set('search_trash', true)
        ->assertSet('operadoras', function ($operadoras): true {
            expect($operadoras)
                ->toHaveCount(2);

            return true;
        });
});

test('pagination of results', function (): void {
    $this->seed(RoleSeeder::class);
    $operadoras = [];

    for ($i = 0; $i < 20; $i++) {
        $empresa = Empresa::factory()->create();
        $empresa->giveOperadora();
        $operadoras[] = $empresa;
    }

    actingAs(
        User::factory()->withRoles('admin')->withPermissions('operadora.list')->create()
    );

    Livewire::test(Index::class)
        ->set('perPage', 15)
        ->assertSet('operadoras', function (LengthAwarePaginator $operadoras): true {
            expect($operadoras)
                ->toHaveCount(15);

            return true;
        });
});
