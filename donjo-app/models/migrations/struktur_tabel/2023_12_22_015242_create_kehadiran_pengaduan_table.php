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
        Schema::create('kehadiran_pengaduan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('kehadiran_pengaduan_config_fk');
            $table->dateTime('waktu');
            $table->boolean('status')->default(false);
            $table->text('keterangan')->nullable();
            $table->integer('id_penduduk');
            $table->integer('id_pamong');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kehadiran_pengaduan');
    }
};
