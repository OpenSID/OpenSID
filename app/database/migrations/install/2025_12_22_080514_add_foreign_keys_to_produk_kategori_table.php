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
        Schema::table('produk_kategori', function (Blueprint $table) {
            $table->foreign(['config_id'], 'produk_kategori_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk_kategori', function (Blueprint $table) {
            $table->dropForeign('produk_kategori_config_fk');
        });
    }
};
