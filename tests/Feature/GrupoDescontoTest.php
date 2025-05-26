<?php

declare(strict_types = 1);

use App\Models\Empresa;
use App\Models\GrupoDesconto;
use App\Models\Regra;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cria um grupo de desconto com os campos obrigatórios', function () {
    $grupo = GrupoDesconto::create([
        'descricao'    => 'Grupo Teste',
        'ativo'        => true,
        'operadora_id' => 1,
    ]);

    expect($grupo)->toBeInstanceOf(GrupoDesconto::class)
        ->and($grupo->descricao)->toBe('Grupo Teste')
        ->and($grupo->ativo)->toBeTrue()
        ->and($grupo->operadora_id)->toBe(1);
});

it('relaciona grupo de desconto com operadora', function () {
    $emprsa = Empresa::factory()->create();
    $emprsa->giveOperadora();
    $operadora = $emprsa->Operadoras()->first();
    $grupo     = GrupoDesconto::factory()->create(['operadora_id' => $operadora->id]);

    expect($grupo->operadoras)->not->toBeNull()
        ->and($grupo->operadoras->id)->toBe($operadora->id);
});

it('relaciona grupo de desconto com regras', function () {
    $grupo = GrupoDesconto::factory()->create();
    $regra = Regra::factory()->create(['grupo_desconto_id' => $grupo->id]);

    expect($grupo->regras)->toHaveCount(1)
        ->and($grupo->regras->first()->id)->toBe($regra->id);
});
