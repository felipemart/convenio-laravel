<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Index;
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
    $emprsa->giveConvenio($emprsa->id);

    actingAs(
        User::factory()->withRoles('admin')->create()
    );

    get(route('conveniada.list'))
        ->assertOk();

    actingAs(
        User::factory()->withRoles('operadora')->create()
    );

    get(route('conveniada.list'))
        ->assertOk();

    actingAs(
        User::factory()->withRoles('convenio')->updateEmpresa($emprsa->id)->create()
    );

    get(route('conveniada.list'))
        ->assertOk();
});

test('nao pode ser acessada pelo que nao tem permissao', function (): void {
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

test('composente deve carregar todos os usuarios', function (): void {
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
        User::factory()->withRoles('convenio')->updateEmpresa($emprsaConvenio->id)->create()
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

test('vefiricando ser a table tem formato', function (): void {
    actingAs(
        User::factory()->withRoles('admin')->create()
    );
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'razao_social', 'label' => 'Razão Social'],
            ['key' => 'nome_fantasia', 'label' => 'Nome Fantasia'],
        ]);
});

test('deve filtar os usuarios por nome e email', function (): void {
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
        User::factory()->withRoles('convenio')->updateEmpresa($emprsaConvenio->id)->create()
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

test('deve filtar os usuarios deletado', function (): void {
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
        User::factory()->withRoles('convenio')->updateEmpresa($emprsaConvenio->id)->create()
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

test('paginacao dos resultados', function (): void {
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
        User::factory()->withRoles('convenio')->updateEmpresa($emprsaConvenio->id)->create()
    );

    Livewire::test(Index::class)
        ->set('perPage', 15)
        ->assertSet('conveniadas', function (LengthAwarePaginator $conveniadas): true {
            expect($conveniadas)
                ->toHaveCount(15);

            return true;
        });
});
