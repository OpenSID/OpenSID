<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ref_syarat_surat', function (Blueprint $table) {
            $table->increments('ref_syarat_id');
            $table->integer('config_id')->index('ref_syarat_surat_config_fk');
            $table->string('ref_syarat_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_syarat_surat');
    }
};
