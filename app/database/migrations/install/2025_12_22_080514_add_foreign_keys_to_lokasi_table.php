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
        Schema::table('lokasi', function (Blueprint $table) {
            $table->foreign(['id_cluster'], 'lokasi_cluster_fk')->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'lokasi_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ref_point'], 'lokasi_point_fk')->references(['id'])->on('point')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropForeign('lokasi_cluster_fk');
            $table->dropForeign('lokasi_config_fk');
            $table->dropForeign('lokasi_point_fk');
        });
    }
};
