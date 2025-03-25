<?php

declare(strict_types = 1);

use App\Livewire\Conveniada\Update;
use App\Models\Empresa;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('should access the conveniada profile', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($empresaConvenio->id);

    $conveniada = $empresaConveniada->conveniadas()->first();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $conveniada->id])
        ->assertSet('razao_social', $empresaConveniada->razao_social)
        ->assertSet('nome_fantasia', $empresaConveniada->nome_fantasia)
        ->assertSet('logradouro', $empresaConveniada->logradouro)
        ->assertSet('bairro', $empresaConveniada->bairro)
        ->assertSet('cep', $empresaConveniada->cep)
        ->assertSet('uf', $empresaConveniada->uf)
        ->assertSet('cidade', $empresaConveniada->cidade)
        ->assertSet('email', $empresaConveniada->email)
        ->assertOk();
});

test('Validation rules', function ($f): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($empresaConvenio->id);

    $conveniada = $empresaConveniada->conveniadas()->first();

    actingAs($admin);

    if ($f->rule == 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $livewire = Livewire::test(Update::class, ['id' => $conveniada->id])
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

it('should update the conveniada', function (): void {
    $admin   = User::factory()->withRoles('admin')->create();
    $empresa = Empresa::factory()->create();
    $empresa->giveOperadora();

    $empresaConvenio = Empresa::factory()->create();
    $empresaConvenio->giveConvenio($empresa->id);

    $empresaConveniada = Empresa::factory()->create();
    $empresaConveniada->giveConveniada($empresaConvenio->id);

    $conveniada = $empresaConveniada->conveniadas()->first();

    actingAs($admin);

    $component = Livewire::test(Update::class, ['id' => $conveniada->id])
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
        'id'            => $conveniada->empresa->id,
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
