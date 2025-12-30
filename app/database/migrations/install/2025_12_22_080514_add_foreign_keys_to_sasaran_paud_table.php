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
        Schema::table('sasaran_paud', function (Blueprint $table) {
            $table->foreign(['config_id'], 'sasaran_paud_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['kia_id'], 'sasaran_paud_kia_fk')->references(['id'])->on('kia')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['posyandu_id'], 'sasaran_paud_posyandu_fk')->references(['id'])->on('posyandu')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sasaran_paud', function (Blueprint $table) {
            $table->dropForeign('sasaran_paud_config_fk');
            $table->dropForeign('sasaran_paud_kia_fk');
            $table->dropForeign('sasaran_paud_posyandu_fk');
        });
    }
};
