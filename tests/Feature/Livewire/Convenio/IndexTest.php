<?php

declare(strict_types = 1);

use App\Livewire\Convenio\Index;
use App\Models\Convenio;
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

    get(route('convenio.list'))
        ->assertOk();

    actingAs(
        User::factory()->withRoles('operadora')->create()
    );

    get(route('convenio.list'))
        ->assertOk();
});

test('nao pode ser acessada pelo que nao tem permissao', function (): void {
    $this->seed(RoleSeeder::class);
    actingAs(
        User::factory()->withRoles('guest')->create()
    );
    get(route('convenio.list'))
        ->assertForbidden();
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsa->giveConvenio($emprsa->id);
    actingAs(
        User::factory()->withRoles('convenio')->create()
    );
    get(route('convenio.list'))
        ->assertForbidden();
});

test('composente deve carregar todos os usuarios', function (): void {
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
        User::factory()->withRoles('operadora')->updateEmpresa($emprsa->id)->create()
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
    $convenios = [];

    for ($i = 0; $i < 2; $i++) {
        $emprsaConvenio = Empresa::factory()->create();
        $emprsaConvenio->giveConvenio($emprsa->id);
        $convenios[] = $emprsaConvenio;
    }

    actingAs(
        User::factory()->withRoles('operadora')->updateEmpresa($emprsa->id)->create()
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

test('deve filtar os usuarios deletado', function (): void {
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
        User::factory()->withRoles('operadora')->updateEmpresa($emprsa->id)->create()
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

test('paginacao dos resultados', function (): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();

    for ($i = 0; $i < 20; $i++) {
        $emprsaConvenio = Empresa::factory()->create();
        $emprsaConvenio->giveConvenio($emprsa->id);
    }

    actingAs(
        User::factory()->withRoles('operadora')->updateEmpresa($emprsa->id)->create()
    );

    Livewire::test(Index::class)
        ->set('perPage', 15)
        ->assertSet('convenios', function (LengthAwarePaginator $convenios): true {
            expect($convenios)
                ->toHaveCount(15);

            return true;
        });
});
