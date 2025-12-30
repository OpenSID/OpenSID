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
        Schema::table('fcm_token_mandiri', function (Blueprint $table) {
            $table->foreign(['config_id'], 'fcm_token_mandiri_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user_mandiri'], 'fcm_token_mandiri_user_mandiri_fk')->references(['id_pend'])->on('tweb_penduduk_mandiri')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fcm_token_mandiri', function (Blueprint $table) {
            $table->dropForeign('fcm_token_mandiri_config_fk');
            $table->dropForeign('fcm_token_mandiri_user_mandiri_fk');
        });
    }
};
