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
        Schema::create('keuangan_ref_sbu', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ref_sbu_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ref_sbu_master_fk');
            $table->string('Kd_Rincian', 100);
            $table->string('Kode_SBU', 100);
            $table->string('NoUrut_SBU', 100);
            $table->string('Nama_SBU', 100);
            $table->string('Nilai', 100);
            $table->string('Satuan', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ref_sbu');
    }
};
