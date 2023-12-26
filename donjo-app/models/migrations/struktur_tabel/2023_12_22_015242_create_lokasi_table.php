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
        Schema::create('lokasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('lokasi_config_fk');
            $table->text('desk');
            $table->string('nama', 50);
            $table->integer('enabled')->default(1);
            $table->string('lat', 30)->nullable();
            $table->string('lng', 30)->nullable();
            $table->integer('ref_point')->index('ref_point');
            $table->string('foto', 100)->nullable();
            $table->integer('id_cluster')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lokasi');
    }
};
