<?php

declare(strict_types = 1);

use App\Models\Role;
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
        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('permission');
            $table->string('descricao');
            $table->foreignIdFor(Role::class);
            $table->timestamps();
        });

        Schema::create('permission_user', function (Blueprint $table): void {
            $table->foreignId('permission_id')
                ->constrained('permissions');
            $table->foreignId('user_id')
                ->constrained('users');
            $table->primary(['permission_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permissions');
    }
};
