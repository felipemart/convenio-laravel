<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table): void {
            $table->id();
            $table->string('cnpj');
            $table->string('nome_fantasia');
            $table->string('razao_social');
            $table->string('abreviatura')->nullable();
            $table->string('cep');
            $table->string('logradouro');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('uf');
            $table->string('telefone');
            $table->string('email');
            $table->string('inscricao_estadual')->nullable();
            $table->string('inscricao_municipal')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete, se necessário
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
