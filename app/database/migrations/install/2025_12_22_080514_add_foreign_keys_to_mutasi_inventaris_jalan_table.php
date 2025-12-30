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
        Schema::table('mutasi_inventaris_jalan', function (Blueprint $table) {
            $table->foreign(['id_inventaris_jalan'], 'FK_mutasi_inventaris_jalan')->references(['id'])->on('inventaris_jalan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'mutasi_inventaris_jalan_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_inventaris_jalan', function (Blueprint $table) {
            $table->dropForeign('FK_mutasi_inventaris_jalan');
            $table->dropForeign('mutasi_inventaris_jalan_config_fk');
        });
    }
};
