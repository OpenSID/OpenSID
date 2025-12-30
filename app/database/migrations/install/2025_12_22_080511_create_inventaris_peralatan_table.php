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
        Schema::create('inventaris_peralatan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('inventaris_peralatan_config_fk');
            $table->string('nama_barang');
            $table->string('kode_barang', 64);
            $table->string('register', 64);
            $table->string('merk')->nullable();
            $table->text('ukuran')->nullable();
            $table->text('bahan')->nullable();
            $table->year('tahun_pengadaan');
            $table->string('no_pabrik')->nullable();
            $table->string('no_rangka')->nullable();
            $table->string('no_mesin')->nullable();
            $table->string('no_polisi')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('asal');
            $table->double('harga', null, 0);
            $table->text('keterangan');
            $table->integer('status')->default(0);
            $table->integer('visible')->default(1);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_peralatan');
    }
};
