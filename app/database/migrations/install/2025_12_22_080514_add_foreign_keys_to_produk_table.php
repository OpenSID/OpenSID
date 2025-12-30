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
        Schema::table('produk', function (Blueprint $table) {
            $table->foreign(['id_pelapak'], 'lapak_fk')->references(['id'])->on('pelapak')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'produk_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_produk_kategori'], 'produk_kategori_fk')->references(['id'])->on('produk_kategori')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign('lapak_fk');
            $table->dropForeign('produk_config_fk');
            $table->dropForeign('produk_kategori_fk');
        });
    }
};
