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
        Schema::table('grup_akses', function (Blueprint $table) {
            $table->foreign(['id_grup'], 'fk_id_grup')->references(['id'])->on('user_grup')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_modul'], 'fk_id_modul')->references(['id'])->on('setting_modul')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'grup_akses_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grup_akses', function (Blueprint $table) {
            $table->dropForeign('fk_id_grup');
            $table->dropForeign('fk_id_modul');
            $table->dropForeign('grup_akses_config_fk');
        });
    }
};
