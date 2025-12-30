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
        Schema::create('buku_kepuasan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('buku_kepuasan_config_fk');
            $table->integer('id_nama')->nullable()->index('buku_kepuasan_nama_fk');
            $table->integer('id_pertanyaan')->nullable()->index('buku_kepuasan_pertanyaan_fk');
            $table->integer('id_jawaban');
            $table->text('pertanyaan_statis')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_kepuasan');
    }
};
