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
        Schema::table('covid19_pemudik', function (Blueprint $table) {
            $table->foreign(['config_id'], 'covid19_pemudik_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_terdata'], 'fk_pemudik_penduduk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('covid19_pemudik', function (Blueprint $table) {
            $table->dropForeign('covid19_pemudik_config_fk');
            $table->dropForeign('fk_pemudik_penduduk');
        });
    }
};
