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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('kode', 100);
            $table->string('judul', 100);
            $table->string('jenis', 50);
            $table->text('isi');
            $table->string('server', 20);
            $table->timestamp('tgl_berikutnya')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('updated_by');
            $table->smallInteger('frekuensi');
            $table->string('aksi', 100);
            $table->tinyInteger('aktif')->default(1);

            $table->unique(['config_id', 'kode'], 'kode_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifikasi');
    }
};
