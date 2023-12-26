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
        Schema::create('keuangan_ta_rab_sub', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_rab_sub_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_rab_sub_master_fk');
            $table->string('Tahun', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Kd_Keg', 100);
            $table->string('Kd_Rincian', 100);
            $table->string('Kd_SubRinci', 100);
            $table->string('Nama_SubRinci', 100);
            $table->string('Anggaran', 100);
            $table->string('AnggaranPAK', 100);
            $table->string('AnggaranStlhPAK', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_rab_sub');
    }
};
