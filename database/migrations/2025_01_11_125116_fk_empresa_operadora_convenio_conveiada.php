<?php

use App\Models\{Conveniada, Convenio, Empresa, Operadora, Role};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignIdFor(Empresa::class, 'empresa_id');
        });
        Schema::table('empresas', function (Blueprint $table) {
            $table->foreignIdFor(Role::class, 'role_id')->nullable();
            $table->foreignIdFor(Operadora::class, 'operadora_id')->nullable();
            $table->foreignIdFor(Convenio::class, 'convenio_id')->nullable();
            $table->foreignIdFor(Conveniada::class, 'conveniada_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('empresa_id');
        });
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('role_id');
            $table->dropColumn('operadora_id');
            $table->dropColumn('convenio_id');
            $table->dropColumn('conveniada_id');
        });
    }
};
