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
        Schema::create('keuangan_ta_pajak', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_pajak_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_pajak_master_fk');
            $table->string('Tahun', 100);
            $table->string('Kd_Desa', 100);
            $table->string('No_SSP', 100);
            $table->string('Tgl_SSP', 100);
            $table->string('Keterangan', 250)->nullable();
            $table->string('Nama_WP', 100);
            $table->string('Alamat_WP', 100);
            $table->string('NPWP', 100);
            $table->string('Kd_MAP', 100);
            $table->string('Nm_Penyetor', 100);
            $table->string('Jn_Transaksi', 100);
            $table->string('Kd_Rincian', 100);
            $table->string('Jumlah', 100);
            $table->string('KdBayar', 100);
            $table->string('ID_Bank', 10)->nullable();
            $table->string('NTPN', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_pajak');
    }
};
