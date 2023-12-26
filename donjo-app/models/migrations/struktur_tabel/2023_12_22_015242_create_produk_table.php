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
        Schema::create('produk', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('produk_config_fk');
            $table->integer('id_pelapak')->nullable()->index('lapak_fk');
            $table->integer('id_produk_kategori')->nullable()->index('produk_kategori_fk');
            $table->string('nama')->nullable();
            $table->integer('harga')->nullable();
            $table->string('satuan', 20)->nullable();
            $table->boolean('tipe_potongan')->nullable()->default(true);
            $table->integer('potongan')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('foto', 225)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
};
