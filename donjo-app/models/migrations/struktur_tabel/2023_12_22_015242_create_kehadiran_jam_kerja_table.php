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
        Schema::create('kehadiran_jam_kerja', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable();
            $table->string('nama_hari', 65);
            $table->time('jam_masuk');
            $table->time('jam_keluar');
            $table->boolean('status')->default(true);
            $table->text('keterangan')->nullable();

            $table->unique(['config_id', 'nama_hari'], 'jam_kerja_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kehadiran_jam_kerja');
    }
};
