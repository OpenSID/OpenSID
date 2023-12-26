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
        Schema::create('mutasi_cdesa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('mutasi_cdesa_config_fk');
            $table->unsignedInteger('id_cdesa_masuk')->nullable()->index('cdesa_mutasi_fk');
            $table->unsignedInteger('cdesa_keluar')->nullable();
            $table->tinyInteger('jenis_mutasi')->nullable();
            $table->date('tanggal_mutasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('id_persil');
            $table->tinyInteger('no_bidang_persil')->nullable();
            $table->decimal('luas', 7, 0)->nullable();
            $table->string('no_objek_pajak', 30)->nullable();
            $table->text('path')->nullable();
            $table->integer('id_peta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_cdesa');
    }
};
