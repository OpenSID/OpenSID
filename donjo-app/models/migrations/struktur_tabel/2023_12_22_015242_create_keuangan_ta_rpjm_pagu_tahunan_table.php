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
        Schema::create('keuangan_ta_rpjm_pagu_tahunan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_rpjm_pagu_tahunan_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_rpjm_pagu_tahunan_master_fk');
            $table->string('Kd_Desa', 100);
            $table->string('Kd_Keg', 100);
            $table->string('Kd_Tahun', 100);
            $table->string('Kd_Sumber', 100);
            $table->string('Biaya', 100);
            $table->string('Volume', 100);
            $table->string('Satuan', 100);
            $table->string('Lokasi_Spesifik', 100);
            $table->string('Jml_Sas_Pria', 100);
            $table->string('Jml_Sas_Wanita', 100);
            $table->string('Jml_Sas_ARTM', 100);
            $table->string('Waktu', 100);
            $table->string('Mulai', 100);
            $table->string('Selesai', 100);
            $table->string('Pola_Kegiatan', 100);
            $table->string('Pelaksana', 100);
            $table->string('No_ID', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_rpjm_pagu_tahunan');
    }
};
