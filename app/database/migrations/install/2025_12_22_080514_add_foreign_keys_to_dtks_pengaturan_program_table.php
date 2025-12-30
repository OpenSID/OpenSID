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
        Schema::table('dtks_pengaturan_program', function (Blueprint $table) {
            $table->foreign(['config_id'], 'dtks_pengaturan_program_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_bantuan'], 'FK_dtks_p_program')->references(['id'])->on('program')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dtks_pengaturan_program', function (Blueprint $table) {
            $table->dropForeign('dtks_pengaturan_program_config_fk');
            $table->dropForeign('FK_dtks_p_program');
        });
    }
};
