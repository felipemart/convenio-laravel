<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Create;
use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

test('render the livewire view', function (): void {
    actingAs(User::factory()->withRoles('admin')->create());
    Livewire::test(Create::class)
        ->assertStatus(200);
});

test('Validation rules', function ($f): void {
    actingAs(User::factory()->withRoles('admin')->create());

    if ($f->rule == 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Create::class)
        ->set($f->field, $f->value);

    if (property_exists($f, 'aValue')) {
        $livewire->set($f->aField, $f->aValue);
    }

    $livewire->call('save')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'cnpj::required'          => (object)['field' => 'cnpj', 'value' => '', 'rule' => 'required'],
    'cnpj::max:14'            => (object)['field' => 'cnpj', 'value' => str_repeat('*', 15), 'rule' => 'max'],
    'cnpj::min:14'            => (object)['field' => 'cnpj', 'value' => str_repeat('*', 13), 'rule' => 'min'],
    'razao_social::required'  => (object)['field' => 'razao_social', 'value' => '', 'rule' => 'required'],
    'nome_fantasia::required' => (object)['field' => 'nome_fantasia', 'value' => '', 'rule' => 'required'],
    'logradouro::required'    => (object)['field' => 'logradouro', 'value' => '', 'rule' => 'required'],
    'bairro::required'        => (object)['field' => 'bairro', 'value' => '', 'rule' => 'required'],
    'cep::required'           => (object)['field' => 'cep', 'value' => '', 'rule' => 'required'],
    'uf::required'            => (object)['field' => 'uf', 'value' => '', 'rule' => 'required'],
    'cidade::required'        => (object)['field' => 'cidade', 'value' => '', 'rule' => 'required'],
    'email::required'         => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
]);

test('Should be able to load data of an existing company', function () {
    $empresa = Empresa::factory()->create();

    Http::fake([
        "https://publica.cnpj.ws/cnpj/{$empresa->cnpj}" => Http::response([
            'razao_social'    => $empresa->razao_social,
            'estabelecimento' => [
                'nome_fantasia' => $empresa->nome_fantasia,
                'logradouro'    => $empresa->logradouro,
                'bairro'        => $empresa->bairro,
                'cep'           => $empresa->cep,
                'cidade'        => [
                    'nome' => $empresa->cidade,
                ],
                'estado' => [
                    'sigla' => $empresa->uf,
                ],
            ],
        ], 200),  // Resposta mockada da API com código HTTP 200
    ]);

    actingAs(User::factory()->withRoles('admin')->create());
    Livewire::test(Create::class)
        ->set('cnpj', $empresa->cnpj)
        ->call('cnpjCarregaDados')
        ->assertSet('razao_social', $empresa->razao_social)
        ->assertSet('nome_fantasia', $empresa->nome_fantasia)
        ->assertSet('logradouro', $empresa->logradouro)
        ->assertSet('bairro', $empresa->bairro)
        ->assertSet('cep', $empresa->cep)
        ->assertSet('uf', $empresa->uf)
        ->assertSet('cidade', $empresa->cidade);
});

test('Should be able to register a new affiliated company in the system', function ($f): void {
    $this->seed(RoleSeeder::class);
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $emprsa->giveConvenio($emprsa->id);

    actingAs(User::factory()->withRoles($f->role)->updateEmpresa($emprsa->id)->create());

    $lw = Livewire::test(Create::class)
        ->set('cnpj', '12345678901234')
        ->set('razao_social', 'Razao social')
        ->set('nome_fantasia', 'Nome fantasia')
        ->set('logradouro', 'Logradouro')
        ->set('bairro', 'Bairro')
        ->set('cep', '12345678')
        ->set('uf', 'UF')
        ->set('cidade', 'Cidade')
        ->set('email', 'LzV9H@example.com');

    if ($f->role != 'convenio') {
        $lw->set('convenioId', $emprsa->id);
    }
    $lw->call('save')
        ->assertHasNoErrors();

    assertDatabaseHas('empresas', [
        'cnpj'          => '12345678901234',
        'razao_social'  => 'Razao social',
        'nome_fantasia' => 'Nome fantasia',
        'logradouro'    => 'Logradouro',
        'bairro'        => 'Bairro',
        'cep'           => '12345678',
        'uf'            => 'UF',
        'cidade'        => 'Cidade',
        'email'         => 'LzV9H@example.com',
    ]);

    assertDatabaseHas('conveniadas', [
        'empresa_id'  => Empresa::where('cnpj', '=', '12345678901234')->first()->id,
        'convenio_id' => Convenio::where('empresa_id', '=', $emprsa->id)->first()->id,
    ]);
})->with([
    'Admin'     => (object)['role' => 'admin'],
    'Operadora' => (object)['role' => 'operadora'],
    'Convenio'  => (object)['role' => 'convenio'],
]);
