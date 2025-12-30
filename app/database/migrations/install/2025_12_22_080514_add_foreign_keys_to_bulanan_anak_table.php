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
        Schema::table('bulanan_anak', function (Blueprint $table) {
            $table->foreign(['config_id'], 'bulanan_anak_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['kia_id'], 'bulanan_anak_kia_fk')->references(['id'])->on('kia')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['posyandu_id'], 'bulanan_anak_posyandu_fk')->references(['id'])->on('posyandu')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulanan_anak', function (Blueprint $table) {
            $table->dropForeign('bulanan_anak_config_fk');
            $table->dropForeign('bulanan_anak_kia_fk');
            $table->dropForeign('bulanan_anak_posyandu_fk');
        });
    }
};
