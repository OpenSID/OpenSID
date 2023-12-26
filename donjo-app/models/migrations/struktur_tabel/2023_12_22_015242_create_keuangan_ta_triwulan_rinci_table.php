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
        Schema::create('keuangan_ta_triwulan_rinci', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_triwulan_rinci_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_triwulan_rinci_master_fk');
            $table->string('KdPosting', 100);
            $table->string('KURincianSD', 100);
            $table->string('Tahun', 100);
            $table->string('Sifat', 100);
            $table->string('SumberDana', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Kd_Keg', 100);
            $table->string('Kd_Rincian', 100);
            $table->string('Anggaran', 100);
            $table->string('AnggaranPAK', 100);
            $table->string('Tw1Rinci', 100)->nullable();
            $table->string('Tw2Rinci', 100)->nullable();
            $table->string('Tw3Rinci', 100)->nullable();
            $table->string('Tw4Rinci', 100)->nullable();
            $table->string('KunciData', 100);
            $table->string('Jan', 100)->nullable();
            $table->string('Peb', 100)->nullable();
            $table->string('Mar', 100)->nullable();
            $table->string('Apr', 100)->nullable();
            $table->string('Mei', 100)->nullable();
            $table->string('Jun', 100)->nullable();
            $table->string('Jul', 100)->nullable();
            $table->string('Agt', 100)->nullable();
            $table->string('Sep', 100)->nullable();
            $table->string('Okt', 100)->nullable();
            $table->string('Nop', 100)->nullable();
            $table->string('Des', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_triwulan_rinci');
    }
};
