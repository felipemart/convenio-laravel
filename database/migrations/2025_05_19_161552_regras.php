<?php

declare(strict_types = 1);

use App\Models\ClassificacaoProduto;
use App\Models\GrupoDesconto;
use App\Models\Produto;
use App\Models\Regra;
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
        Schema::create('regras', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(GrupoDesconto::class, 'grupo_desconto_id');
            $table->integer('permite_venda')->default(0);
            $table->float('descrento_com_crm');
            $table->float('desconto_sem_crm');
            $table->float('subsidio_com_crm');
            $table->float('subsidio_sem_crm');
            $table->integer('validade_receita');
            $table->integer('crm');
            $table->integer('receita');
            $table->integer('ativo');
            $table->integer('tipo')->default(0);
            $table->datetime('dt_inicio')->nullable();
            $table->datetime('dt_fim')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('regras_associadas', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Produto::class, 'produto_id')->nullable();
            $table->foreignIdFor(ClassificacaoProduto::class, 'classificacao_id')->nullable();
            $table->foreignIdFor(Regra::class, 'regra_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regras');
    }
};
