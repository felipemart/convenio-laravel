<?php

declare(strict_types = 1);

use App\Livewire\Operadora\Update;
use App\Models\Empresa;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('deve acessar o perfil da conveniada', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $operadora->id])
        ->assertSet('razao_social', $empresa->razao_social)
        ->assertSet('nome_fantasia', $empresa->nome_fantasia)
        ->assertSet('logradouro', $empresa->logradouro)
        ->assertSet('bairro', $empresa->bairro)
        ->assertSet('cep', $empresa->cep)
        ->assertSet('uf', $empresa->uf)
        ->assertSet('cidade', $empresa->cidade)
        ->assertSet('email', $empresa->email)
        ->assertOk();
});

test('Regras de validacao', function ($f): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();

    if ($f->rule == 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Update::class, ['id' => $operadora->id])
        ->set($f->field, $f->value);

    if (property_exists($f, 'aValue')) {
        $livewire->set($f->aField, $f->aValue);
    }

    $livewire->call('save')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'cnpj::required'          => (object) ['field' => 'cnpj', 'value' => '', 'rule' => 'required'],
    'cnpj::max:14'            => (object) ['field' => 'cnpj', 'value' => str_repeat('*', 15), 'rule' => 'max'],
    'cnpj::min:14'            => (object) ['field' => 'cnpj', 'value' => str_repeat('*', 13), 'rule' => 'min'],
    'razao_social::required'  => (object) ['field' => 'razao_social', 'value' => '', 'rule' => 'required'],
    'nome_fantasia::required' => (object) ['field' => 'nome_fantasia', 'value' => '', 'rule' => 'required'],
    'logradouro::required'    => (object) ['field' => 'logradouro', 'value' => '', 'rule' => 'required'],
    'bairro::required'        => (object) ['field' => 'bairro', 'value' => '', 'rule' => 'required'],
    'cep::required'           => (object) ['field' => 'cep', 'value' => '', 'rule' => 'required'],
    'uf::required'            => (object) ['field' => 'uf', 'value' => '', 'rule' => 'required'],
    'cidade::required'        => (object) ['field' => 'cidade', 'value' => '', 'rule' => 'required'],
    'email::required'         => (object) ['field' => 'email', 'value' => '', 'rule' => 'required'],
]);

it('deve atualizar a conveniada', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $operadora = $empresa->operadora()->first();
    $component = Livewire::test(Update::class, ['id' => $operadora->id])
        ->set('cnpj', '12345678000195')
        ->set('nome_fantasia', 'Nome Fantasia Teste')
        ->set('razao_social', 'Razão Social Teste')
        ->set('logradouro', 'Logradouro Teste')
        ->set('bairro', 'Bairro Teste')
        ->set('cep', '12345678')
        ->set('uf', 'SP')
        ->set('cidade', 'Cidade Teste')
        ->set('email', 'teste@example.com');

    // Act
    $component->call('save');

    // Assert
    $this->assertDatabaseHas('empresas', [
        'id'            => $operadora->empresa->id,
        'cnpj'          => '12345678000195',
        'nome_fantasia' => 'Nome Fantasia Teste',
        'razao_social'  => 'Razão Social Teste',
        'logradouro'    => 'Logradouro Teste',
        'bairro'        => 'Bairro Teste',
        'cep'           => '12345678',
        'uf'            => 'SP',
        'cidade'        => 'Cidade Teste',
        'email'         => 'teste@example.com',
    ]);
});
