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
        Schema::table('cdesa_penduduk', function (Blueprint $table) {
            $table->foreign(['config_id'], 'cdesa_penduduk_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_cdesa'], 'cdesa_penduduk_fk')->references(['id'])->on('cdesa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pend'], 'cdesa_penduduk_pend_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cdesa_penduduk', function (Blueprint $table) {
            $table->dropForeign('cdesa_penduduk_config_fk');
            $table->dropForeign('cdesa_penduduk_fk');
            $table->dropForeign('cdesa_penduduk_pend_fk');
        });
    }
};
