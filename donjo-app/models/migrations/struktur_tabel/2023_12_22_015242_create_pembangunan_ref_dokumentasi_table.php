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
        Schema::create('pembangunan_ref_dokumentasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('pembangunan_ref_dokumentasi_config_fk');
            $table->integer('id_pembangunan')->index('id_pembangunan');
            $table->string('gambar')->nullable();
            $table->string('persentase')->nullable();
            $table->string('keterangan')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembangunan_ref_dokumentasi');
    }
};
