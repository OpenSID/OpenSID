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
        Schema::table('log_tolak', function (Blueprint $table) {
            $table->foreign(['config_id'], 'log_tolak_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_surat_dinas'], 'log_tolak_surat_dinas_fk')->references(['id'])->on('log_surat_dinas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_surat'], 'log_tolak_surat_fk')->references(['id'])->on('log_surat')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_tolak', function (Blueprint $table) {
            $table->dropForeign('log_tolak_config_fk');
            $table->dropForeign('log_tolak_surat_dinas_fk');
            $table->dropForeign('log_tolak_surat_fk');
        });
    }
};
