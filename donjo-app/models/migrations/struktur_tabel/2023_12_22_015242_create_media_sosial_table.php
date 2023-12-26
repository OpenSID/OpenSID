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
        Schema::create('media_sosial', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->text('gambar');
            $table->text('link')->nullable();
            $table->string('nama', 100);
            $table->boolean('tipe')->nullable()->default(true);
            $table->integer('enabled');

            $table->unique(['config_id', 'nama'], 'media_sosial_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_sosial');
    }
};
