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
        Schema::create('tweb_penduduk_map', function (Blueprint $table) {
            $table->integer('id')->nullable()->index('tweb_penduduk_map_pend_fk');
            $table->string('lat', 24)->nullable();
            $table->string('lng', 24)->nullable();
            $table->integer('config_id')->nullable()->index('tweb_penduduk_map_config_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_penduduk_map');
    }
};
