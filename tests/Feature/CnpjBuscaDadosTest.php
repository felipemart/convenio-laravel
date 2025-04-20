<?php

declare(strict_types = 1);

use App\Actions\CnpjBuscaDados;

it('returns company data when the API call is successful', function () {
    Illuminate\Support\Facades\Http::fake([
        'https://publica.cnpj.ws/cnpj/12345678000195' => Http::response([
            'razao_social'    => 'Empresa Exemplo',
            'estabelecimento' => [
                'nome_fantasia' => 'Exemplo Fantasia',
                'logradouro'    => 'Rua Exemplo',
                'bairro'        => 'Bairro Exemplo',
                'cep'           => '12345-678',
                'estado'        => ['sigla' => 'SP'],
                'cidade'        => ['nome' => 'São Paulo'],
            ],
        ], 200),
    ]);

    $action = new CnpjBuscaDados();
    $result = $action->execute('12345678000195');

    expect($result)->toBe([
        'razao_social'  => 'Empresa Exemplo',
        'nome_fantasia' => 'Exemplo Fantasia',
        'logradouro'    => 'Rua Exemplo',
        'bairro'        => 'Bairro Exemplo',
        'cep'           => '12345-678',
        'uf'            => 'SP',
        'cidade'        => 'São Paulo',
    ]);
});

it('returns empty data when the API call fails', function () {
    Http::fake([
        'https://publica.cnpj.ws/cnpj/12345678000195' => Http::response([], 404),
    ]);

    $action = new CnpjBuscaDados();
    $result = $action->execute('12345678000195');

    expect($result)->toBe([
        'razao_social'  => '',
        'nome_fantasia' => '',
        'logradouro'    => '',
        'bairro'        => '',
        'cep'           => '',
        'uf'            => '',
        'cidade'        => '',
    ]);
});
