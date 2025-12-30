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
        Schema::create('mutasi_inventaris_tanah', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('mutasi_inventaris_tanah_config_fk');
            $table->integer('id_inventaris_tanah')->nullable()->index('fk_mutasi_inventaris_tanah');
            $table->string('jenis_mutasi', 100)->nullable();
            $table->date('tahun_mutasi');
            $table->double('harga_jual', null, 0)->nullable();
            $table->string('sumbangkan')->nullable();
            $table->text('keterangan');
            $table->integer('visible')->default(1);
            $table->string('status_mutasi', 50);
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
        Schema::dropIfExists('mutasi_inventaris_tanah');
    }
};
