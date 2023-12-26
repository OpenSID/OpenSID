<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tanah_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('tanah_desa_config_fk');
            $table->integer('id_penduduk')->index('id_penduduk');
            $table->decimal('nik', 16, 0)->nullable();
            $table->text('jenis_pemilik')->nullable();
            $table->string('nama_pemilik_asal', 200);
            $table->integer('luas');
            $table->integer('hak_milik')->nullable();
            $table->integer('hak_guna_bangunan')->nullable();
            $table->integer('hak_pakai')->nullable();
            $table->integer('hak_guna_usaha')->nullable();
            $table->integer('hak_pengelolaan')->nullable();
            $table->integer('hak_milik_adat')->nullable();
            $table->integer('hak_verponding')->nullable();
            $table->integer('tanah_negara')->nullable();
            $table->integer('perumahan')->nullable();
            $table->integer('perdagangan_jasa')->nullable();
            $table->integer('perkantoran')->nullable();
            $table->integer('industri')->nullable();
            $table->integer('fasilitas_umum')->nullable();
            $table->integer('sawah')->nullable();
            $table->integer('tegalan')->nullable();
            $table->integer('perkebunan')->nullable();
            $table->integer('peternakan_perikanan')->nullable();
            $table->integer('hutan_belukar')->nullable();
            $table->integer('hutan_lebat_lindung')->nullable();
            $table->integer('tanah_kosong')->nullable();
            $table->integer('lain')->nullable();
            $table->text('mutasi');
            $table->text('keterangan');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by');
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('updated_by');
            $table->tinyInteger('visible')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tanah_desa');
    }
};
