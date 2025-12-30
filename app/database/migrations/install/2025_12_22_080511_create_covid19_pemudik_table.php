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
        Schema::create('covid19_pemudik', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('covid19_pemudik_config_fk');
            $table->integer('id_terdata')->nullable()->index('fk_pemudik_penduduk');
            $table->boolean('pantau')->default(true);
            $table->date('tanggal_datang')->nullable();
            $table->string('asal_mudik')->nullable();
            $table->string('durasi_mudik', 50)->nullable();
            $table->string('tujuan_mudik')->nullable();
            $table->string('keluhan_kesehatan')->nullable();
            $table->string('status_covid', 50)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('is_wajib_pantau', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covid19_pemudik');
    }
};
