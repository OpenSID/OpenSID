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
        Schema::create('agenda', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('agenda_config_fk');
            $table->integer('id_artikel')->index('id_artikel_fk');
            $table->timestamp('tgl_agenda')->useCurrent();
            $table->string('koordinator_kegiatan', 50);
            $table->string('lokasi_kegiatan', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
