<?php

declare(strict_types = 1);

// Removido strict_types para compatibilidade com PHP < 7.0

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() // Removido : void para compatibilidade
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table      = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->create($table, function (Blueprint $table) { // Removido : void
            $morphPrefix = config('audit.user.morph_prefix', 'user');

            $table->bigIncrements('id');
            $table->string($morphPrefix . '_type')->nullable();
            $table->unsignedBigInteger($morphPrefix . '_id')->nullable();
            $table->string('event');
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 1023)->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();

            $table->index([$morphPrefix . '_id', $morphPrefix . '_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() // Removido : void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table      = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->drop($table);
    }
}
