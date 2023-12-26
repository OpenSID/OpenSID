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
        Schema::create('laporan_sinkronisasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('laporan_sinkronisasi_config_fk');
            $table->string('tipe', 50)->nullable();
            $table->string('judul', 100);
            $table->integer('tahun');
            $table->integer('semester');
            $table->string('nama_file', 100);
            $table->dateTime('kirim')->nullable();
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
        Schema::dropIfExists('laporan_sinkronisasi');
    }
};
