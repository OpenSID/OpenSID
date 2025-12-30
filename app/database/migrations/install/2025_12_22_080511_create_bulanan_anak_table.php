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
        Schema::create('bulanan_anak', function (Blueprint $table) {
            $table->increments('id_bulanan_anak');
            $table->integer('config_id')->index('bulanan_anak_config_fk');
            $table->integer('posyandu_id')->nullable()->index('bulanan_anak_posyandu_fk');
            $table->integer('kia_id')->nullable()->index('bulanan_anak_kia_fk');
            $table->boolean('status_gizi');
            $table->tinyInteger('umur_bulan');
            $table->boolean('status_tikar');
            $table->boolean('pemberian_imunisasi_dasar');
            $table->boolean('pemberian_imunisasi_campak')->nullable();
            $table->boolean('pengukuran_berat_badan');
            $table->double('berat_badan', null, 0)->nullable();
            $table->boolean('pengukuran_tinggi_badan');
            $table->double('tinggi_badan', null, 0)->nullable();
            $table->boolean('konseling_gizi_ayah');
            $table->boolean('konseling_gizi_ibu');
            $table->boolean('kunjungan_rumah');
            $table->boolean('air_bersih');
            $table->boolean('kepemilikan_jamban');
            $table->boolean('akta_lahir');
            $table->boolean('jaminan_kesehatan');
            $table->boolean('pengasuhan_paud');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('bulanan_anak');
    }
};
