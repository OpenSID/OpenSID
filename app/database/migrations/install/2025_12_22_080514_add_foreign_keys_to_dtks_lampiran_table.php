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
        Schema::table('dtks_lampiran', function (Blueprint $table) {
            $table->foreign(['config_id'], 'dtks_lampiran_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_rtm'], 'FK_dtks_lampiran_rtm')->references(['id'])->on('tweb_rtm')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dtks_lampiran', function (Blueprint $table) {
            $table->dropForeign('dtks_lampiran_config_fk');
            $table->dropForeign('FK_dtks_lampiran_rtm');
        });
    }
};
