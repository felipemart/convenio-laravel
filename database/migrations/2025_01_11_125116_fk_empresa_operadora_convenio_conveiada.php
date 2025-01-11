<?php

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
            $table->foreignId('empresa_id');
        });
        Schema::table('empresas', function (Blueprint $table) {
            $table->foreignId('operadora_id')->nullable();
            $table->foreignId('convenio_id')->nullable();
            $table->foreignId('conveniada_id')->nullable();
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
            $table->dropColumn('operadora_id')->nullable();
            $table->dropColumn('convenio_id')->nullable();
            $table->dropColumn('conveniada_id')->nullable();
        });
    }
};
