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
        Schema::table('kia', function (Blueprint $table) {
            $table->foreign(['anak_id'], 'kia_anak_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'kia_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['ibu_id'], 'kia_ibu_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kia', function (Blueprint $table) {
            $table->dropForeign('kia_anak_fk');
            $table->dropForeign('kia_config_fk');
            $table->dropForeign('kia_ibu_fk');
        });
    }
};
