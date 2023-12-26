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
        Schema::create('keuangan_ta_jurnal_umum_rinci', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_jurnal_umum_rinci_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_jurnal_umum_rinci_master_fk');
            $table->string('Tahun', 100);
            $table->string('NoBukti', 100);
            $table->string('Kd_Keg', 100);
            $table->string('RincianSD', 100);
            $table->string('NoID', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Akun', 100);
            $table->string('Kd_Rincian', 100);
            $table->string('Sumberdana', 100);
            $table->string('DK', 100);
            $table->string('Debet', 100);
            $table->string('Kredit', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_jurnal_umum_rinci');
    }
};
