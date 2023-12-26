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
        Schema::create('keuangan_ta_pemda', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_pemda_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_pemda_master_fk');
            $table->string('Tahun', 100);
            $table->string('Kd_Prov', 100);
            $table->string('Kd_Kab', 100);
            $table->string('Nama_Pemda', 100);
            $table->string('Nama_Provinsi', 100);
            $table->string('Ibukota', 100);
            $table->string('Alamat', 100);
            $table->string('Nm_Bupati', 100);
            $table->string('Jbt_Bupati', 100);
            $table->binary('Logo')->nullable();
            $table->string('C_Kode', 100);
            $table->string('C_Pemda', 100);
            $table->string('C_Data', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_pemda');
    }
};
