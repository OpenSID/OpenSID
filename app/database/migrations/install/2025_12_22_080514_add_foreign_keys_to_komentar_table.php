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
        Schema::table('komentar', function (Blueprint $table) {
            $table->foreign(['id_artikel'], 'komentar_artikel_fk')->references(['id'])->on('artikel')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'komentar_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komentar', function (Blueprint $table) {
            $table->dropForeign('komentar_artikel_fk');
            $table->dropForeign('komentar_config_fk');
        });
    }
};
