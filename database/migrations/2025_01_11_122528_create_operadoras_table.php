<?php

declare(strict_types = 1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('operadoras', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('empresa_id'); // Chave estrangeira para empresas (operadoras)
            $table->timestamps();
            $table->softDeletes();
            $table->datetime('restored_at')->nullable();
            $table->foreignIdFor(User::class, 'restored_by')->nullable();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operadoras');
    }
};
