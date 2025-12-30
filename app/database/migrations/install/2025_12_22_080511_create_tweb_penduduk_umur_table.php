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
        Schema::create('tweb_penduduk_umur', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('tweb_penduduk_umur_config_fk');
            $table->string('nama', 25)->nullable();
            $table->integer('dari')->nullable();
            $table->integer('sampai')->nullable();
            $table->integer('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_penduduk_umur');
    }
};
