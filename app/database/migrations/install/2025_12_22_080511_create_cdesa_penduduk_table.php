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
        Schema::create('cdesa_penduduk', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('cdesa_penduduk_config_fk');
            $table->unsignedInteger('id_cdesa')->index('id_cdesa');
            $table->integer('id_pend')->nullable()->index('cdesa_penduduk_pend_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdesa_penduduk');
    }
};
