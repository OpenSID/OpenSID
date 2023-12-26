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
        Schema::create('analisis_respon_hasil', function (Blueprint $table) {
            $table->tinyInteger('id_master');
            $table->integer('config_id')->nullable()->index('analisis_respon_hasil_config_fk');
            $table->tinyInteger('id_periode');
            $table->integer('id_subjek');
            $table->double('akumulasi', 8, 3);
            $table->timestamp('tgl_update')->useCurrent();

            $table->unique(['id_master', 'id_periode', 'id_subjek'], 'id_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_respon_hasil');
    }
};
