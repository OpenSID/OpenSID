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
        Schema::table('agenda', function (Blueprint $table) {
            $table->foreign(['config_id'], 'agenda_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_artikel'], 'id_artikel_fk')->references(['id'])->on('artikel')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropForeign('agenda_config_fk');
            $table->dropForeign('id_artikel_fk');
        });
    }
};
