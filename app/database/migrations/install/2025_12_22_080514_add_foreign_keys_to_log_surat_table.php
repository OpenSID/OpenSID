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
        Schema::table('log_surat', function (Blueprint $table) {
            $table->foreign(['config_id'], 'log_surat_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_format_surat'], 'log_surat_format_surat_fk')->references(['id'])->on('tweb_surat_format')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pamong'], 'log_surat_pamong_fk')->references(['pamong_id'])->on('tweb_desa_pamong')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['urls_id'], 'log_surat_pamong_urls_fk')->references(['id'])->on('urls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pend'], 'log_surat_pend_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'log_surat_user_fk')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_surat', function (Blueprint $table) {
            $table->dropForeign('log_surat_config_fk');
            $table->dropForeign('log_surat_format_surat_fk');
            $table->dropForeign('log_surat_pamong_fk');
            $table->dropForeign('log_surat_pamong_urls_fk');
            $table->dropForeign('log_surat_pend_fk');
            $table->dropForeign('log_surat_user_fk');
        });
    }
};
