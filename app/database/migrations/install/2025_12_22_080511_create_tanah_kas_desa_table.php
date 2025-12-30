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
        Schema::create('tanah_kas_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('tanah_kas_desa_config_fk');
            $table->string('nama_pemilik_asal', 200);
            $table->text('letter_c');
            $table->text('kelas');
            $table->integer('luas');
            $table->integer('asli_milik_desa')->nullable();
            $table->integer('pemerintah')->nullable();
            $table->integer('provinsi')->nullable();
            $table->integer('kabupaten_kota')->nullable();
            $table->integer('lain_lain')->nullable();
            $table->integer('sawah')->nullable();
            $table->integer('tegal')->nullable();
            $table->integer('kebun')->nullable();
            $table->integer('tambak_kolam')->nullable();
            $table->integer('tanah_kering_darat')->nullable();
            $table->integer('ada_patok')->nullable();
            $table->integer('tidak_ada_patok')->nullable();
            $table->integer('ada_papan_nama')->nullable();
            $table->integer('tidak_ada_papan_nama')->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->text('lokasi');
            $table->text('peruntukan');
            $table->text('mutasi');
            $table->text('keterangan');
            $table->tinyInteger('visible')->default(1);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanah_kas_desa');
    }
};
