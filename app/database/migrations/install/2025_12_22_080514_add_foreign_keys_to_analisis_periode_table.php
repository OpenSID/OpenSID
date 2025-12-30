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
        Schema::table('analisis_periode', function (Blueprint $table) {
            $table->foreign(['config_id'], 'analisis_periode_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_master'], 'analisis_periode_master_fk')->references(['id'])->on('analisis_master')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_state'], 'state_fk')->references(['id'])->on('analisis_ref_state')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisis_periode', function (Blueprint $table) {
            $table->dropForeign('analisis_periode_config_fk');
            $table->dropForeign('analisis_periode_master_fk');
            $table->dropForeign('state_fk');
        });
    }
};
