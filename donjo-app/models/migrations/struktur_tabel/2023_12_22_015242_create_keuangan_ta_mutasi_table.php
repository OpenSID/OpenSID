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
        Schema::create('keuangan_ta_mutasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_mutasi_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_mutasi_master_fk');
            $table->string('Tahun', 100);
            $table->string('Kd_Desa', 100);
            $table->string('No_Bukti', 100);
            $table->string('Tgl_Bukti', 100);
            $table->string('Keterangan', 200)->nullable();
            $table->string('Kd_Bank', 100)->nullable();
            $table->string('Kd_Rincian', 100);
            $table->string('Kd_Keg', 100);
            $table->string('Sumberdana', 100);
            $table->string('Kd_Mutasi', 100);
            $table->string('Nilai', 100);
            $table->string('ID_Bank', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_mutasi');
    }
};
