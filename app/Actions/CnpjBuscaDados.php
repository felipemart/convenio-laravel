<?php

declare(strict_types = 1);

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class CnpjBuscaDados
{
    public function execute(string $cnpj): array
    {
        $response = Http::get("https://publica.cnpj.ws/cnpj/{$cnpj}");

        if ($response->status() == 200) {
            $e = json_decode($response->body());

            return [
                'razao_social'  => $e->razao_social,
                'nome_fantasia' => $e->estabelecimento->nome_fantasia ?: '',
                'logradouro'    => $e->estabelecimento->logradouro,
                'bairro'        => $e->estabelecimento->bairro,
                'cep'           => $e->estabelecimento->cep,
                'uf'            => $e->estabelecimento->estado->sigla,
                'cidade'        => $e->estabelecimento->cidade->nome,
            ];
        }

        return [
            'razao_social'  => '',
            'nome_fantasia' => '',
            'logradouro'    => '',
            'bairro'        => '',
            'cep'           => '',
            'uf'            => '',
            'cidade'        => '',
        ];
    }
}
