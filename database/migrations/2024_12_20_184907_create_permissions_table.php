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
            $table->foreignIdFor(Role::class);
            $table->timestamps();
        });

        Schema::create('permission_user', function (Blueprint $table): void {
            $table->foreignId('user_id');
            $table->foreignId('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->unique(['user_id', 'permission_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_user');
    }
};
