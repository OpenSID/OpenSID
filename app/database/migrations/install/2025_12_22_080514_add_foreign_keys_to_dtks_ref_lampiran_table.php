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
        Schema::table('dtks_ref_lampiran', function (Blueprint $table) {
            $table->foreign(['config_id'])->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_lampiran'], 'FK_lampiran_dtks')->references(['id'])->on('dtks_lampiran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_dtks'], 'FK_ref_lampiran_dtks')->references(['id'])->on('dtks')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dtks_ref_lampiran', function (Blueprint $table) {
            $table->dropForeign('dtks_ref_lampiran_config_id_foreign');
            $table->dropForeign('FK_lampiran_dtks');
            $table->dropForeign('FK_ref_lampiran_dtks');
        });
    }
};
