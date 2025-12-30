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
        Schema::table('mutasi_inventaris_tanah', function (Blueprint $table) {
            $table->foreign(['config_id'], 'mutasi_inventaris_tanah_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_inventaris_tanah'], 'mutasi_inventaris_tanah_inventaris_tanah_fk')->references(['id'])->on('inventaris_tanah')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_inventaris_tanah', function (Blueprint $table) {
            $table->dropForeign('mutasi_inventaris_tanah_config_fk');
            $table->dropForeign('mutasi_inventaris_tanah_inventaris_tanah_fk');
        });
    }
};
