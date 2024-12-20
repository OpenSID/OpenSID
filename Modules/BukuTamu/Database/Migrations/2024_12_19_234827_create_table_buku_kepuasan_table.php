<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\BukuTamu\Models\KepuasanModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelapak', function (Blueprint $table) {
            $table->id();
            $table->config();
            $table->unsignedBigInteger('id_nama')->nullable();
            $table->unsignedBigInteger('id_pertanyaan')->nullable();
            $table->unsignedBigInteger('id_jawaban');
            $table->mediumText('pertanyaan_statis')->nullable();
            $table->timestamps();

            $table->foreign('id_nama')
                ->references('id')
                ->on('buku_tamu')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_pertanyaan')
                ->references('id')
                ->on('buku_pertanyaan')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->index('id_nama', 'buku_kepuasan_nama_fk');
            $table->index('id_pertanyaan', 'buku_kepuasan_pertanyaan_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('pelapak', function () {
            KepuasanModel::withoutConfigId(identitas('id'))->delete();
        });
    }
};
