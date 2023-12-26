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
        Schema::create('kelompok', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->integer('id_master')->index('id_master');
            $table->integer('id_ketua')->index('id_ketua');
            $table->string('nama', 50);
            $table->string('slug')->nullable();
            $table->string('keterangan', 300)->nullable();
            $table->string('kode', 16);
            $table->string('tipe', 100)->nullable()->default('kelompok');

            $table->unique(['config_id', 'slug'], 'slug_config');
            $table->unique(['config_id', 'kode'], 'kode_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelompok');
    }
};
