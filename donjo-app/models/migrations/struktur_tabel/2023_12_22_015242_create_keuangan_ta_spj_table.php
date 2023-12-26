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
        Schema::create('keuangan_ta_spj', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_spj_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_spj_master_fk');
            $table->string('Tahun', 100);
            $table->string('No_SPJ', 100);
            $table->string('Tgl_SPJ', 100);
            $table->string('Kd_Desa', 100);
            $table->string('No_SPP', 100);
            $table->string('Keterangan')->nullable();
            $table->string('Jumlah', 100);
            $table->string('Potongan', 100);
            $table->string('Status', 100);
            $table->string('Kunci', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_spj');
    }
};
