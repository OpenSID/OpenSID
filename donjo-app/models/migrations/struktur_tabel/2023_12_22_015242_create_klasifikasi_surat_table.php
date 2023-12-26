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
        Schema::create('klasifikasi_surat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('klasifikasi_surat_config_fk');
            $table->string('kode', 50);
            $table->string('nama', 250);
            $table->mediumText('uraian');
            $table->integer('enabled')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('klasifikasi_surat');
    }
};
