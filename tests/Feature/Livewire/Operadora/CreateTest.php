<?php

declare(strict_types = 1);

use App\Livewire\Operadora\Create;
use App\Models\Empresa;
use App\Models\User;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

test('renderizar a view do livewire', function (): void {
    actingAs(User::factory()->withRoles('admin')->create());
    Livewire::test(Create::class)
        ->assertStatus(200);
});

test('Regras de validacao', function ($f): void {
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

test('Devera ser capaz de carregar os dados de empresa ja existente', function () {
    $empresa = Empresa::factory()->create();
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
        ->assertSet('cidade', $empresa->cidade)
        ->assertSet('email', $empresa->email);
})->skip();

test('Devera ser capaz de registrar uma nova operadora no sistema', function ($f): void {
    $this->seed(RoleSeeder::class);

    actingAs(User::factory()->withRoles($f->role)->create());

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

    assertDatabaseHas('operadoras', [
        'empresa_id' => Empresa::where('cnpj', '=', '12345678901234')->first()->id,
    ]);
})->with([
    'Admin' => (object)['role' => 'admin'],
]);
