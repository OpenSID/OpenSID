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
        Schema::create('artikel', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('artikel_config_fk');
            $table->string('gambar', 200)->nullable();
            $table->longText('isi');
            $table->integer('enabled')->default(1);
            $table->timestamp('tgl_upload')->useCurrent();
            $table->integer('id_kategori')->nullable()->index('artikel_kategori_fk');
            $table->integer('id_user')->nullable()->index('artikel_kategori_id_user_fk');
            $table->string('judul', 200);
            $table->boolean('headline')->default(false);
            $table->string('gambar1', 200)->nullable();
            $table->string('gambar2', 200)->nullable();
            $table->string('gambar3', 200)->nullable();
            $table->string('dokumen', 400)->nullable();
            $table->string('link_dokumen', 200)->nullable();
            $table->boolean('boleh_komentar')->default(true);
            $table->string('slug', 200)->nullable();
            $table->integer('hit')->nullable()->default(0);
            $table->tinyInteger('tampilan')->nullable()->default(1);
            $table->boolean('slider')->default(false);
            $table->string('tipe', 50)->default('dinamis');
            $table->integer('urut')->nullable();
            $table->tinyInteger('jenis_widget')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
