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
        Schema::create('keuangan_ta_perangkat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_perangkat_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_perangkat_master_fk');
            $table->string('Tahun', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Kd_Jabatan', 100);
            $table->string('No_ID', 100);
            $table->string('Nama_Perangkat', 100);
            $table->string('Alamat_Perangkat', 100);
            $table->string('Nomor_HP', 100);
            $table->string('Rek_Bank', 100);
            $table->string('Nama_Bank', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_perangkat');
    }
};
