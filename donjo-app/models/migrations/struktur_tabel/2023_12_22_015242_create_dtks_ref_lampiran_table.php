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
        Schema::create('dtks_ref_lampiran', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_dtks')->index('FK_ref_lampiran_dtks');
            $table->integer('id_lampiran')->index('FK_lampiran_dtks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dtks_ref_lampiran');
    }
};
