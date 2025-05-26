<?php

declare(strict_types = 1);

use App\Models\Operadora;
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
        Schema::create('grupo_descontos', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Operadora::class, 'operadora_id');
            $table->string('descricao');
            $table->integer('ativo')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_descontos');
    }
};
