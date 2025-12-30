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
        Schema::create('log_notifikasi_mandiri', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user_mandiri')->nullable()->index('log_notifikasi_mandiri_user_mandiri_fk');
            $table->integer('config_id')->index('log_notifikasi_mandiri_config_fk');
            $table->string('judul')->comment('Judul notifikasi');
            $table->text('isi')->comment('Isi notifikasi');
            $table->longText('token')->nullable();
            $table->longText('device')->nullable();
            $table->string('image')->nullable()->comment('gambar notifikasi, jika ada');
            $table->string('payload', 100)->comment('Tujuan navicasi saat notifikasi di klik');
            $table->tinyInteger('read')->comment('menandatakan notifikasi sudah terbaca atau belum, 1 artinya sudah dibaca, 0 artinya belum dibaca');
            $table->timestamps();

            $table->index(['id', 'created_at', 'read', 'config_id'], 'log_notifikasi_mandiri_id_created_at_read_device_config_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_notifikasi_mandiri');
    }
};
