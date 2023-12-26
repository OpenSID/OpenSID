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
        Schema::create('permohonan_surat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('permohonan_surat_config_fk');
            $table->integer('id_pemohon');
            $table->integer('id_surat');
            $table->text('isian_form');
            $table->boolean('status')->default(false);
            $table->string('alasan', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('no_hp_aktif', 50);
            $table->text('syarat');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('no_antrian', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permohonan_surat');
    }
};
