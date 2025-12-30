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
        Schema::table('hubung_warga', function (Blueprint $table) {
            $table->foreign(['config_id'], 'hubung_warga_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_grup'], 'hubung_warga_id_grup_fk')->references(['id_grup'])->on('kontak_grup')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hubung_warga', function (Blueprint $table) {
            $table->dropForeign('hubung_warga_config_fk');
            $table->dropForeign('hubung_warga_id_grup_fk');
        });
    }
};
