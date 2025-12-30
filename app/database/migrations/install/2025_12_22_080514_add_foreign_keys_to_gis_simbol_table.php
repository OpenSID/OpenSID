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
        Schema::table('gis_simbol', function (Blueprint $table) {
            $table->foreign(['config_id'], 'gis_simbol_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gis_simbol', function (Blueprint $table) {
            $table->dropForeign('gis_simbol_config_fk');
        });
    }
};
