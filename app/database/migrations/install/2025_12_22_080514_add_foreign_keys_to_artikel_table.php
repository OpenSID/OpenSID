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
        Schema::table('artikel', function (Blueprint $table) {
            $table->foreign(['config_id'], 'artikel_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_kategori'], 'artikel_kategori_fk')->references(['id'])->on('kategori')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'artikel_kategori_id_user_fk')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropForeign('artikel_config_fk');
            $table->dropForeign('artikel_kategori_fk');
            $table->dropForeign('artikel_kategori_id_user_fk');
        });
    }
};
