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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('dokumen_config_fk');
            $table->string('satuan', 200)->nullable();
            $table->string('nama', 200);
            $table->integer('enabled')->default(1);
            $table->timestamp('tgl_upload')->useCurrent();
            $table->integer('id_pend')->default(0);
            $table->tinyInteger('kategori')->default(1);
            $table->text('attr');
            $table->tinyInteger('tipe')->nullable()->default(1);
            $table->text('url')->nullable();
            $table->integer('tahun')->nullable();
            $table->tinyInteger('kategori_info_publik')->nullable();
            $table->timestamp('updated_at')->useCurrent();
            $table->boolean('deleted')->default(false);
            $table->integer('id_syarat')->nullable();
            $table->integer('id_parent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('created_by', 16)->nullable();
            $table->string('updated_by', 16)->nullable();
            $table->boolean('dok_warga')->nullable()->default(false);
            $table->string('lokasi_arsip', 150)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dokumen');
    }
};
