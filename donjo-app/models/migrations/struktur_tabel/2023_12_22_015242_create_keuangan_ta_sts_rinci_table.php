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
        Schema::create('keuangan_ta_sts_rinci', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_sts_rinci_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_sts_rinci_master_fk');
            $table->string('Tahun', 100);
            $table->string('Kd_Desa', 100);
            $table->string('No_Bukti', 100);
            $table->string('No_TBP', 100);
            $table->string('Uraian', 100);
            $table->string('Nilai', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_sts_rinci');
    }
};
