<?php

declare(strict_types = 1);

use App\Services\CnpjBuscaDados;

it('returns company data when the API call is successful', function (): void {
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

    $serviceCnpj = new CnpjBuscaDados('12345678000195');
    $result      = $serviceCnpj->buscarDados();

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

it('returns empty data when the API call fails', function (): void {
    Http::fake([
        'https://publica.cnpj.ws/cnpj/12345678000195' => Http::response([], 404),
    ]);

    $serviceCnpj = new CnpjBuscaDados('12345678000195');
    $result      = $serviceCnpj->buscarDados();

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
