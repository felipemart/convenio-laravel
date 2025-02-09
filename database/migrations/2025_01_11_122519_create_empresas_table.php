<?php

declare(strict_types = 1);

use App\Models\Empresa;
use App\Models\Role;
use App\Models\User;
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
        Schema::create('empresas', function (Blueprint $table): void {
            $table->id();
            $table->string('cnpj');
            $table->string('nome_fantasia');
            $table->string('razao_social');
            $table->string('abreviatura');
            $table->string('cep');
            $table->string('logradouro');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('uf');
            $table->string('telefone');
            $table->string('email');
            $table->string('inscricao_estadual')->nullable();
            $table->string('inscricao_municipal');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
            $table->foreignIdFor(Role::class, 'role_id')->nullable();
            $table->foreignIdFor(Empresa::class, 'operadora_id')->nullable();
            $table->foreignIdFor(Empresa::class, 'convenio_id')->nullable();
            $table->foreignIdFor(Empresa::class, 'conveniada_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
