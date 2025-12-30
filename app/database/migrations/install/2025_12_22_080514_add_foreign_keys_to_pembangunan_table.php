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
        Schema::table('pembangunan', function (Blueprint $table) {
            $table->foreign(['config_id'], 'pembangunan_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_lokasi'], 'pembangunan_lokasi_cluster_fk')->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembangunan', function (Blueprint $table) {
            $table->dropForeign('pembangunan_config_fk');
            $table->dropForeign('pembangunan_lokasi_cluster_fk');
        });
    }
};
