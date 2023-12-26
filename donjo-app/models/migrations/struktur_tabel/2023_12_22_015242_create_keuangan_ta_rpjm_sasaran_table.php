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
        Schema::create('keuangan_ta_rpjm_sasaran', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_rpjm_sasaran_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_rpjm_sasaran_master_fk');
            $table->string('ID_Sasaran', 100);
            $table->string('Kd_Desa', 100);
            $table->string('ID_Tujuan', 100);
            $table->string('No_Sasaran', 100);
            $table->string('Uraian_Sasaran', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_rpjm_sasaran');
    }
};
