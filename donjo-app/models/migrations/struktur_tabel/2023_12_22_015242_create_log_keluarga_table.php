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
        Schema::create('log_keluarga', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->integer('id_kk');
            $table->integer('id_peristiwa');
            $table->timestamp('tgl_peristiwa')->useCurrent();
            $table->integer('id_pend')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('id_log_penduduk')->nullable()->index('log_penduduk_fk');

            $table->unique(['config_id', 'id_kk', 'id_peristiwa', 'tgl_peristiwa', 'id_pend'], 'id_kk_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_keluarga');
    }
};
