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
        Schema::create('keuangan_ta_spp', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_spp_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_spp_master_fk');
            $table->string('Tahun', 100);
            $table->string('No_SPP', 100);
            $table->string('Tgl_SPP', 100);
            $table->string('Jn_SPP', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Keterangan', 250)->nullable();
            $table->string('Jumlah', 100);
            $table->string('Potongan', 100);
            $table->string('Status', 100);
            $table->string('F10', 10)->nullable();
            $table->string('F11', 10)->nullable();
            $table->string('FF12', 10)->nullable();
            $table->string('FF13', 10)->nullable();
            $table->string('FF14', 10)->nullable();
            $table->string('Kd_Bank', 100)->nullable();
            $table->string('Nm_Bank', 100)->nullable();
            $table->string('Nm_Penerima', 100)->nullable();
            $table->string('Ref_Bayar', 100)->nullable();
            $table->string('Rek_Bank', 100)->nullable();
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
        Schema::dropIfExists('keuangan_ta_spp');
    }
};
