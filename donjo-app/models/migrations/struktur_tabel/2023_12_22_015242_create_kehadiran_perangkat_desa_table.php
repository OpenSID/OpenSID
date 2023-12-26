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
        Schema::create('kehadiran_perangkat_desa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('kehadiran_perangkat_desa_config_fk');
            $table->date('tanggal')->nullable();
            $table->integer('pamong_id')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('status_kehadiran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kehadiran_perangkat_desa');
    }
};
