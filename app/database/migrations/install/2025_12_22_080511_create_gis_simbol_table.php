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
        Schema::create('gis_simbol', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id');
            $table->string('simbol', 40)->nullable();

            $table->unique(['config_id', 'simbol'], 'simbol_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gis_simbol');
    }
};
