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
        Schema::create('keuangan_ta_anggaran_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_anggaran_log_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_anggaran_log_master_fk');
            $table->string('KdPosting', 100);
            $table->string('Tahun', 100);
            $table->string('Kd_Desa', 100);
            $table->string('No_Perdes', 100);
            $table->string('TglPosting', 100);
            $table->string('UserID', 50);
            $table->string('Kunci', 100);
            $table->string('No_Perkades', 100)->nullable();
            $table->string('Petugas', 80)->nullable();
            $table->string('Tanggal', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_anggaran_log');
    }
};
