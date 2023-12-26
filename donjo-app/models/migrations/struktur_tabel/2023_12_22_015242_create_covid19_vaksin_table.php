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
        Schema::create('covid19_vaksin', function (Blueprint $table) {
            $table->integer('id_penduduk')->primary();
            $table->integer('config_id')->nullable()->index('covid19_vaksin_config_fk');
            $table->integer('vaksin_1')->nullable();
            $table->date('tgl_vaksin_1')->nullable();
            $table->string('dokumen_vaksin_1')->nullable();
            $table->string('jenis_vaksin_1', 100)->nullable();
            $table->integer('vaksin_2')->nullable();
            $table->date('tgl_vaksin_2')->nullable();
            $table->string('dokumen_vaksin_2')->nullable();
            $table->string('jenis_vaksin_2', 100)->nullable();
            $table->integer('vaksin_3')->nullable();
            $table->date('tgl_vaksin_3')->nullable();
            $table->string('dokumen_vaksin_3')->nullable();
            $table->string('jenis_vaksin_3', 100)->nullable();
            $table->integer('tunda')->nullable();
            $table->tinyText('keterangan')->nullable();
            $table->string('surat_dokter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('covid19_vaksin');
    }
};
