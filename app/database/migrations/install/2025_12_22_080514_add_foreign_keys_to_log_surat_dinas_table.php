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
        Schema::table('log_surat_dinas', function (Blueprint $table) {
            $table->foreign(['config_id'], 'log_surat_dinas_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['created_by'], 'log_surat_dinas_created_by_fk')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_format_surat'], 'log_surat_dinas_format_fk')->references(['id'])->on('surat_dinas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['updated_by'], 'log_surat_dinas_updated_by_fk')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'log_surat_dinas_user_fk')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_surat_dinas', function (Blueprint $table) {
            $table->dropForeign('log_surat_dinas_config_fk');
            $table->dropForeign('log_surat_dinas_created_by_fk');
            $table->dropForeign('log_surat_dinas_format_fk');
            $table->dropForeign('log_surat_dinas_updated_by_fk');
            $table->dropForeign('log_surat_dinas_user_fk');
        });
    }
};
