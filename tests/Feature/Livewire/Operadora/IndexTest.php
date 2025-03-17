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

test('deve ser acessada somente pelos usaurios papeis', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();

    actingAs(
        User::factory()->withRoles('admin')->withPermissions('operadora.list')->create()
    );

    get(route('operadora.list'))
        ->assertOk();
});

test('nao pode ser acessada pelo que nao tem permissao', function (): void {
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

test('composente deve carregar todos os usuarios', function (): void {
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

test('vefiricando ser a table tem formato', function (): void {
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

test('deve filtar os usuarios por cnpj', function (): void {
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

test('deve filtar os usuarios deletado', function (): void {
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

test('paginacao dos resultados', function (): void {
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
