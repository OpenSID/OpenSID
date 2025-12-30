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
        Schema::create('grup_akses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('grup_akses_config_fk');
            $table->integer('id_grup')->index('id_grup');
            $table->integer('id_modul')->index('id_modul');
            $table->tinyInteger('akses')->nullable();

            $table->unique(['config_id', 'id_grup', 'id_modul'], 'config_idid_grupid_modul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_akses');
    }
};
