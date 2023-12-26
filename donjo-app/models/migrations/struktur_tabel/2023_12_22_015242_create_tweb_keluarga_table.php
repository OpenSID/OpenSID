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
        Schema::create('tweb_keluarga', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->string('nik_kepala', 200)->nullable()->index('nik_kepala');
            $table->timestamp('tgl_daftar')->nullable()->useCurrent();
            $table->integer('kelas_sosial')->nullable();
            $table->dateTime('tgl_cetak_kk')->nullable();
            $table->string('alamat', 200)->nullable();
            $table->integer('id_cluster');
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('updated_by')->nullable();

            $table->unique(['config_id', 'no_kk'], 'no_kk_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweb_keluarga');
    }
};
