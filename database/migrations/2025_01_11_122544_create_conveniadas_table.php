<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('conveniadas', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('convenio_id'); // Chave estrangeira para convênios
            $table->unsignedBigInteger('empresa_id'); // Chave estrangeira para empresas (conveniadas)
            $table->timestamps();
            $table->softDeletes(); // Soft delete, se necessário

            $table->foreign('convenio_id')->references('id')->on('convenios');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conveniadas');
    }
};
