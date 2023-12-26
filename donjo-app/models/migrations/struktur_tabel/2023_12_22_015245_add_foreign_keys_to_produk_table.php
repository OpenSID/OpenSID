<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->foreign(['config_id'], 'produk_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_pelapak'], 'lapak_fk')->references(['id'])->on('pelapak')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['id_produk_kategori'], 'produk_kategori_fk')->references(['id'])->on('produk_kategori')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign('produk_config_fk');
            $table->dropForeign('lapak_fk');
            $table->dropForeign('produk_kategori_fk');
        });
    }
};
