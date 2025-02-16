<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('convenios', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('operadora_id'); // Chave estrangeira para operadoras
            $table->unsignedBigInteger('empresa_id'); // Chave estrangeira para empresas (convênios)
            $table->timestamps();
            $table->softDeletes(); // Soft delete, se necessário

            $table->foreign('operadora_id')->references('id')->on('operadoras');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convenios');
    }
};
