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
        Schema::create('cdesa_penduduk', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->nullable()->index('cdesa_penduduk_config_fk');
            $table->unsignedInteger('id_cdesa')->index('id_cdesa');
            $table->integer('id_pend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cdesa_penduduk');
    }
};
