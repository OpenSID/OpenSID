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
        Schema::create('analisis_parameter', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('analisis_parameter_config_fk');
            $table->integer('id_indikator')->index('id_indikator');
            $table->string('jawaban', 200);
            $table->integer('nilai')->default(0);
            $table->integer('kode_jawaban')->nullable()->default(0);
            $table->boolean('asign')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_parameter');
    }
};
