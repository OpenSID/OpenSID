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
        Schema::create('suplemen_terdata', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('suplemen_terdata_config_fk');
            $table->integer('id_suplemen')->nullable()->index('id_suplemen');
            $table->string('id_terdata', 20)->nullable();
            $table->tinyInteger('sasaran')->nullable();
            $table->string('keterangan', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suplemen_terdata');
    }
};
