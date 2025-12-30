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
        Schema::create('tweb_surat_format', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id');
            $table->string('nama', 100);
            $table->string('url_surat', 100);
            $table->string('kode_surat', 10)->nullable();
            $table->string('lampiran', 100)->nullable();
            $table->boolean('kunci')->default(false);
            $table->boolean('favorit')->default(false);
            $table->tinyInteger('jenis')->default(2);
            $table->boolean('mandiri')->nullable()->default(false);
            $table->integer('masa_berlaku')->nullable()->default(1);
            $table->string('satuan_masa_berlaku', 15)->nullable()->default('M');
            $table->boolean('qr_code')->default(false);
            $table->boolean('qr_code_tte')->default(false);
            $table->boolean('logo_garuda')->default(false);
            $table->boolean('kecamatan')->default(false);
            $table->longText('syarat_surat')->nullable();
            $table->longText('template')->nullable();
            $table->longText('template_desa')->nullable();
            $table->longText('form_isian')->nullable();
            $table->longText('kode_isian')->nullable();
            $table->string('orientasi', 10)->nullable();
            $table->string('ukuran', 10)->nullable();
            $table->text('margin')->nullable();
            $table->boolean('margin_global')->nullable()->default(false);
            $table->integer('footer')->default(1);
            $table->integer('header')->default(1);
            $table->string('format_nomor', 100)->nullable();
            $table->tinyInteger('format_nomor_global')->nullable()->default(1);
            $table->boolean('sumber_penduduk_berulang')->nullable()->default(false);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

            $table->unique(['config_id', 'url_surat'], 'url_surat_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweb_surat_format');
    }
};
