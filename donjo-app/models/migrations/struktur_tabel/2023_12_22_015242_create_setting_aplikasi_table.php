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
        Schema::create('setting_aplikasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('judul', 100)->nullable();
            $table->string('key', 50)->nullable();
            $table->text('value')->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->string('jenis', 30)->nullable();
            $table->text('option')->nullable();
            $table->text('attribute')->nullable();
            $table->string('kategori', 30)->nullable();

            $table->unique(['config_id', 'key'], 'key_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_aplikasi');
    }
};
