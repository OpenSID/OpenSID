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
        Schema::create('log_notifikasi_mandiri', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumInteger('id_user_mandiri')->comment('id user mandiri');
            $table->integer('config_id');
            $table->string('judul')->comment('Judul notifikasi');
            $table->text('isi')->comment('Isi notifikasi');
            $table->string('image')->nullable()->comment('gambar notifikasi, jika ada');
            $table->string('payload', 100)->comment('Tujuan navicasi saat notifikasi di klik');
            $table->tinyInteger('read')->comment('menandatakan notifikasi sudah terbaca atau belum, 1 artinya sudah dibaca, 0 artinya belum dibaca');
            $table->timestamps();

            $table->index(['id', 'created_at', 'read', 'config_id'], 'log_notifikasi_mandiri_id_created_at_read_device_config_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_notifikasi_mandiri');
    }
};
