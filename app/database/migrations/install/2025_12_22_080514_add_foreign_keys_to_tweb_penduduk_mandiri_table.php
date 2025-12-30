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
        Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
            $table->foreign(['config_id'], 'tweb_penduduk_mandiri_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pend'], 'tweb_penduduk_mandiri_penduduk_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
            $table->dropForeign('tweb_penduduk_mandiri_config_fk');
            $table->dropForeign('tweb_penduduk_mandiri_penduduk_fk');
        });
    }
};
