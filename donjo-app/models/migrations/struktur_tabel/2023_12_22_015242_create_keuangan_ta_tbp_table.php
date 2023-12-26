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
        Schema::create('keuangan_ta_tbp', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_tbp_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_tbp_master_fk');
            $table->string('Tahun', 100);
            $table->string('No_Bukti', 100);
            $table->string('Tgl_Bukti', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Uraian', 250)->nullable();
            $table->string('Nm_Penyetor', 100);
            $table->string('Alamat_Penyetor', 100);
            $table->string('TTD_Penyetor', 100);
            $table->string('NoRek_Bank', 100);
            $table->string('Nama_Bank', 100);
            $table->string('Jumlah', 100);
            $table->string('Nm_Bendahara', 100);
            $table->string('Jbt_Bendahara', 100);
            $table->string('Status', 100);
            $table->string('KdBayar', 100);
            $table->string('Ref_Bayar', 100);
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
        Schema::dropIfExists('keuangan_ta_tbp');
    }
};
