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
        Schema::table('buku_kepuasan', function (Blueprint $table) {
            $table->foreign(['config_id'], 'buku_kepuasan_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_nama'], 'buku_kepuasan_nama_fk')->references(['id'])->on('buku_tamu')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pertanyaan'], 'buku_kepuasan_pertanyaan_fk')->references(['id'])->on('buku_pertanyaan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_kepuasan', function (Blueprint $table) {
            $table->dropForeign('buku_kepuasan_config_fk');
            $table->dropForeign('buku_kepuasan_nama_fk');
            $table->dropForeign('buku_kepuasan_pertanyaan_fk');
        });
    }
};
