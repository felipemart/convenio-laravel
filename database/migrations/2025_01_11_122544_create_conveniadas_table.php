<?php

declare(strict_types = 1);

use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('conveniadas', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Empresa::class, 'empresa_id');
            $table->foreignIdFor(Convenio::class, 'convenio_id');
            $table->timestamps();
            $table->softDeletes(); // Soft delete, se necessário
            $table->datetime('restored_at')->nullable();
            $table->foreignIdFor(User::class, 'restored_by')->nullable();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conveniadas');
    }
};
