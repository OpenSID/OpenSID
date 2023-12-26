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
        Schema::create('keuangan_ta_rpjm_visi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_rpjm_visi_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_rpjm_visi_master_fk');
            $table->string('ID_Visi', 100);
            $table->string('Kd_Desa', 100);
            $table->string('No_Visi', 100);
            $table->string('No_RPJM', 100)->nullable();
            $table->string('Tgl_RPJM', 100)->nullable();
            $table->string('Uraian_Visi', 250)->nullable();
            $table->string('TahunA', 100);
            $table->string('TahunN', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_rpjm_visi');
    }
};
