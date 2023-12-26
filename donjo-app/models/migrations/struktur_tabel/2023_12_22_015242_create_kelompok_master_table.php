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
        Schema::create('kelompok_master', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('kelompok_master_config_fk');
            $table->string('kelompok', 50);
            $table->string('deskripsi', 400);
            $table->string('tipe', 100)->nullable()->default('kelompok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelompok_master');
    }
};
