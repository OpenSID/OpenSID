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
        Schema::create('keuangan_ta_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_desa_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_desa_master_fk');
            $table->string('Tahun', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Nm_Kades', 100);
            $table->string('Jbt_Kades', 100);
            $table->string('Nm_Sekdes', 100);
            $table->string('NIP_Sekdes', 100);
            $table->string('Jbt_Sekdes', 100);
            $table->string('Nm_Kaur_Keu', 100);
            $table->string('Jbt_Kaur_Keu', 100);
            $table->string('Nm_Bendahara', 100);
            $table->string('Jbt_Bendahara', 100);
            $table->string('No_Perdes', 100);
            $table->string('Tgl_Perdes', 100);
            $table->string('No_Perdes_PB', 100);
            $table->string('Tgl_Perdes_PB', 100);
            $table->string('No_Perdes_PJ', 100);
            $table->string('Tgl_Perdes_PJ', 100);
            $table->string('Alamat', 250);
            $table->string('Ibukota', 100);
            $table->string('Status', 100);
            $table->string('NPWP', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_desa');
    }
};
