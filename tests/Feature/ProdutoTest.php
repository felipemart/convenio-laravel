<?php

declare(strict_types = 1);

use App\Models\ClassificacaoProduto;
use App\Models\Empresa;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cria um produto com os campos obrigatórios', function (): void {
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $operadora             = $emprsa->Operadoras()->first();
    $produto               = new Produto();
    $produto->descricao    = 'Produto Teste';
    $produto->ean_id       = '1234567890123';
    $produto->codigo       = 'P001';
    $produto->pr_maximo    = 99.99;
    $produto->operadora_id = $operadora->id;
    $produto->save();

    expect($produto)->toBeInstanceOf(Produto::class)
        ->and($produto->descricao)->toBe('Produto Teste')
        ->and($produto->ean_id)->toBe('1234567890123')
        ->and($produto->codigo)->toBe('P001')
        ->and($produto->pr_maximo)->toBe(99.99);
});

it('relaciona produto com classificacoes', function (): void {
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $operadora             = $emprsa->Operadoras()->first();
    $produto               = new Produto();
    $produto->descricao    = 'Produto Teste';
    $produto->ean_id       = '1234567890123';
    $produto->codigo       = 'P001';
    $produto->pr_maximo    = 99.99;
    $produto->operadora_id = $operadora->id;
    $produto->save();
    $classificacao = ClassificacaoProduto::factory()->create();

    $produto->ClassificacaoProdutos()->attach($classificacao->id);

    expect($produto->ClassificacaoProdutos)->toHaveCount(1)
        ->and($produto->ClassificacaoProdutos->first()->id)->toBe($classificacao->id);
});

it('relaciona produto com operadora', function (): void {
    $emprsa = Empresa::factory()->create();
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $operadora = $emprsa->Operadoras()->first();
    $produto   = Produto::factory()->create(['operadora_id' => $operadora->id]);

    expect($produto->Operadoras)->not->toBeNull()
        ->and($produto->Operadoras->id)->toBe($operadora->id);
});
