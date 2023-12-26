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
        Schema::create('analisis_respon', function (Blueprint $table) {
            $table->integer('id_indikator')->index('id_indikator');
            $table->integer('config_id')->nullable()->index('analisis_respon_config_fk');
            $table->integer('id_parameter');
            $table->integer('id_subjek');
            $table->integer('id_periode')->index('id_periode');

            $table->index(['id_parameter', 'id_subjek'], 'id_parameter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_respon');
    }
};
