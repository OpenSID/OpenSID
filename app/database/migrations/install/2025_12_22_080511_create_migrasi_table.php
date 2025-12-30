<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('migrasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('versi_database', 10);
            $table->text('premium')->nullable();
            $table->integer('config_id');

            $table->unique(['config_id', 'versi_database'], 'versi_database_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('migrasi');
    }
};
