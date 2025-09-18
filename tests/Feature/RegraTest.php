<?php

declare(strict_types = 1);

use App\Models\Regra;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cria uma regra com os campos obrigatórios com data', function (): void {
    $data      = now()->startOfDay();
    $dataFinal = now()->addDays(10)->startOfDay();
    $regra     = Regra::create([
        'grupo_desconto_id' => 1,
        'permite_venda'     => 1,
        'descrento_com_crm' => 10.5,
        'desconto_sem_crm'  => 5.0,
        'subsidio_com_crm'  => 2.5,
        'subsidio_sem_crm'  => 1.0,
        'validade_receita'  => 30,
        'crm'               => '123456',
        'receita'           => 'Receita Teste',
        'ativo'             => 1,
        'tipo'              => 1,
        'dt_inicio'         => $data,
        'dt_fim'            => $dataFinal,
    ]);

    $regra->refresh();

    expect($regra)->toBeInstanceOf(Regra::class)
        ->and($regra->grupo_desconto_id)->toBe(1)
        ->and($regra->permite_venda)->toBe(1)
        ->and($regra->ativo)->toBe(1)
        ->and($regra->tipo)->toBe(1)
        ->and($regra->dt_inicio)->toBeInstanceOf(DateTimeInterface::class)
        ->and($regra->dt_fim)->toBeInstanceOf(DateTimeInterface::class)
        ->and($regra->dt_inicio->format('Y-m-d H:i:s'))->toBe($data->format('Y-m-d H:i:s'))
        ->and($regra->dt_fim->format('Y-m-d H:i:s'))->toBe($dataFinal->format('Y-m-d H:i:s'));
});

it('cria uma regra com os campos obrigatórios', function (): void {
    $regra = Regra::create([
        'grupo_desconto_id' => 1,
        'permite_venda'     => true,
        'descrento_com_crm' => 10.5,
        'desconto_sem_crm'  => 5.0,
        'subsidio_com_crm'  => 2.5,
        'subsidio_sem_crm'  => 1.0,
        'validade_receita'  => 30,
        'crm'               => '123456',
        'receita'           => 'Receita Teste',
        'ativo'             => true,
        'tipo'              => 0,
    ]);

    expect($regra)->toBeInstanceOf(Regra::class)
        ->and($regra->grupo_desconto_id)->toBe(1)
        ->and($regra->permite_venda)->toBeTrue()
        ->and($regra->ativo)->toBeTrue()
        ->and($regra->tipo)->toBe(0)
        ->and($regra->dt_inicio)->toBeNull()
        ->and($regra->dt_fim)->toBeNull();
});

it('usa soft deletes', function (): void {
    $regra = Regra::create([
        'grupo_desconto_id' => 1,
        'permite_venda'     => true,
        'descrento_com_crm' => 10.5,
        'desconto_sem_crm'  => 5.0,
        'subsidio_com_crm'  => 2.5,
        'subsidio_sem_crm'  => 1.0,
        'validade_receita'  => 30,
        'crm'               => '123456',
        'receita'           => 'Receita Teste',
        'ativo'             => true,
        'tipo'              => 0,
    ]);
    $regra->delete();

    expect(Regra::withTrashed()->find($regra->id))->not->toBeNull()
        ->and(Regra::find($regra->id))->toBeNull();
});
