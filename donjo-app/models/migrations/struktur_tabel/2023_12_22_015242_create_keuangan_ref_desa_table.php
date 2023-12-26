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
        Schema::create('keuangan_ref_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ref_desa_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ref_desa_master_fk');
            $table->string('Kd_Kec', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Nama_Desa', 250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ref_desa');
    }
};
