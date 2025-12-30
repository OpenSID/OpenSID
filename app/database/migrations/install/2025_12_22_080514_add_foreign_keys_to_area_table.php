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
        Schema::table('area', function (Blueprint $table) {
            $table->foreign(['id_cluster'], 'area_cluster_fk')->references(['id'])->on('tweb_wil_clusterdesa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'area_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('area', function (Blueprint $table) {
            $table->dropForeign('area_cluster_fk');
            $table->dropForeign('area_config_fk');
        });
    }
};
