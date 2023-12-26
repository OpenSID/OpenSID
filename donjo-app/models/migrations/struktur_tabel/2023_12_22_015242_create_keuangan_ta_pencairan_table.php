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
        Schema::create('keuangan_ta_pencairan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_pencairan_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_pencairan_master_fk');
            $table->string('Tahun', 100);
            $table->string('No_Cek', 100);
            $table->string('No_SPP', 100);
            $table->string('Tgl_Cek', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Keterangan', 250)->nullable();
            $table->string('Jumlah', 100);
            $table->string('Potongan', 100);
            $table->string('KdBayar', 100);
            $table->string('ID_Bank', 10)->nullable();
            $table->string('Kunci', 10)->nullable();
            $table->string('No_Ref', 100)->nullable();
            $table->string('Tgl_Bayar', 100)->nullable();
            $table->string('Validasi', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_pencairan');
    }
};
