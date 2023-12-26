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
        Schema::create('ref_syarat_surat', function (Blueprint $table) {
            $table->increments('ref_syarat_id');
            $table->integer('config_id')->nullable()->index('ref_syarat_surat_config_fk');
            $table->string('ref_syarat_nama');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_syarat_surat');
    }
};
