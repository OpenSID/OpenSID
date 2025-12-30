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
        Schema::create('pesan_mandiri', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->integer('config_id')->index('pesan_mandiri_config_id_foreign');
            $table->string('owner', 50);
            $table->integer('penduduk_id')->index('pesan_mandiri_penduduk_id_foreign');
            $table->tinyText('subjek')->nullable();
            $table->text('komentar');
            $table->timestamp('tgl_upload')->useCurrent();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('tipe')->nullable();
            $table->text('permohonan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->tinyInteger('is_archived')->nullable()->default(0);

            $table->unique(['uuid', 'config_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_mandiri');
    }
};
