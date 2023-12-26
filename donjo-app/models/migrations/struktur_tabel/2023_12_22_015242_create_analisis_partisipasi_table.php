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
        Schema::create('analisis_partisipasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_subjek');
            $table->integer('id_master')->index('id_master');
            $table->integer('id_periode')->index('id_periode');
            $table->integer('id_klassifikasi')->default(1)->index('id_klassifikasi');

            $table->index(['id_subjek', 'id_master', 'id_periode', 'id_klassifikasi'], 'id_subjek');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analisis_partisipasi');
    }
};
