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
        Schema::create('keuangan_ta_anggaran', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_anggaran_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_anggaran_master_fk');
            $table->string('KdPosting', 100);
            $table->string('Tahun', 100);
            $table->string('KURincianSD', 100);
            $table->string('KD_Rincian', 100);
            $table->string('RincianSD', 100);
            $table->string('Anggaran', 100);
            $table->string('AnggaranPAK', 100);
            $table->string('AnggaranStlhPAK', 100);
            $table->string('Belanja', 100);
            $table->string('Kd_keg', 100);
            $table->string('SumberDana', 100);
            $table->string('Kd_Desa', 100);
            $table->string('TglPosting', 100);
            $table->string('Kd_SubRinci', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_anggaran');
    }
};
