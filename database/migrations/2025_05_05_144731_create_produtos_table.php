<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table): void {
            $table->id();
            $table->string('descricao');
            $table->string('codigo')->unique();
            $table->float('preco');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('classificacao_produto_produto', function (Blueprint $table): void {
            $table->foreignId('classificacao_produto_id')
                ->constrained('classificacao_produtos');
            $table->foreignId('produto_id')
                ->constrained('produtos');
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['classificacao_produto_id', 'produto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classificacao_produto_produto');
        Schema::dropIfExists('produtos');
    }
};
