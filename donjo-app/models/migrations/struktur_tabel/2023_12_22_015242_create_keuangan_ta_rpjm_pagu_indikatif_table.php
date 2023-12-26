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
        Schema::create('keuangan_ta_rpjm_pagu_indikatif', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_rpjm_pagu_indikatif_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_rpjm_pagu_indikatif_master_fk');
            $table->string('Kd_Desa', 100);
            $table->string('Kd_Keg', 100);
            $table->string('Kd_Sumber', 100);
            $table->string('Tahun1', 100);
            $table->string('Tahun2', 100);
            $table->string('Tahun3', 100);
            $table->string('Tahun4', 100);
            $table->string('Tahun5', 100);
            $table->string('Tahun6', 100);
            $table->string('Pola', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_rpjm_pagu_indikatif');
    }
};
