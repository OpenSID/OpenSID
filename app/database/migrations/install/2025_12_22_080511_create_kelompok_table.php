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
        Schema::create('kelompok', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id');
            $table->integer('id_master')->index('id_master');
            $table->integer('id_ketua')->nullable()->index('id_ketua');
            $table->string('nama', 50);
            $table->string('slug')->nullable();
            $table->string('keterangan', 300)->nullable();
            $table->string('kode', 16);
            $table->string('logo')->nullable();
            $table->string('no_sk_pendirian')->nullable();
            $table->string('tipe', 100)->nullable()->default('kelompok');

            $table->unique(['config_id', 'kode', 'tipe'], 'config_kode_tipe');
            $table->unique(['slug', 'config_id'], 'slug_config_tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok');
    }
};
