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
        Schema::table('mutasi_inventaris_peralatan', function (Blueprint $table) {
            $table->foreign(['id_inventaris_peralatan'], 'FK_mutasi_inventaris_peralatan')->references(['id'])->on('inventaris_peralatan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'mutasi_inventaris_peralatan_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_inventaris_peralatan', function (Blueprint $table) {
            $table->dropForeign('FK_mutasi_inventaris_peralatan');
            $table->dropForeign('mutasi_inventaris_peralatan_config_fk');
        });
    }
};
