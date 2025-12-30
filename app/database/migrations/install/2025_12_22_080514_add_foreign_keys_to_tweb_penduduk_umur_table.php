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
        Schema::table('tweb_penduduk_umur', function (Blueprint $table) {
            $table->foreign(['config_id'], 'tweb_penduduk_umur_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweb_penduduk_umur', function (Blueprint $table) {
            $table->dropForeign('tweb_penduduk_umur_config_fk');
        });
    }
};
