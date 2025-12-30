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
        Schema::table('tweb_wil_clusterdesa', function (Blueprint $table) {
            $table->foreign(['config_id'], 'tweb_wil_clusterdesa_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_kepala'], 'tweb_wil_clusterdesa_kepala_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweb_wil_clusterdesa', function (Blueprint $table) {
            $table->dropForeign('tweb_wil_clusterdesa_config_fk');
            $table->dropForeign('tweb_wil_clusterdesa_kepala_fk');
        });
    }
};
