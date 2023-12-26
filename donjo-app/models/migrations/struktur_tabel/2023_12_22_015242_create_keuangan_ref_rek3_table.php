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
        Schema::create('keuangan_ref_rek3', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ref_rek3_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ref_rek3_master_fk');
            $table->string('Kelompok', 100);
            $table->string('Jenis', 100);
            $table->string('Nama_Jenis', 100);
            $table->integer('Formula');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ref_rek3');
    }
};
