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
        Schema::create('kelompok_master', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('kelompok_master_config_fk');
            $table->string('kelompok', 50);
            $table->text('deskripsi');
            $table->string('tipe', 100)->nullable()->default('kelompok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_master');
    }
};
