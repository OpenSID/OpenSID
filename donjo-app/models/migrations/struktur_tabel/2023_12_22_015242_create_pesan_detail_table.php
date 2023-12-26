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
        Schema::create('pesan_detail', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('config_id')->nullable()->index('pesan_detail_config_fk');
            $table->integer('pesan_id');
            $table->text('text');
            $table->string('pengirim', 100)->nullable();
            $table->string('nama_pengirim', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesan_detail');
    }
};
