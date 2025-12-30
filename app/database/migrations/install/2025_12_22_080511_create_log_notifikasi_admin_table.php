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
        Schema::create('log_notifikasi_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->nullable()->index('log_notifikasi_admin_user_fk');
            $table->integer('config_id')->index('log_notifikasi_admin_config_fk');
            $table->string('judul');
            $table->text('isi');
            $table->longText('token')->nullable();
            $table->longText('device')->nullable();
            $table->string('image')->nullable();
            $table->string('payload');
            $table->integer('read');
            $table->timestamps();

            $table->index(['id', 'created_at', 'read', 'config_id'], 'log_notifikasi_admin_id_created_at_read_device_config_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_notifikasi_admin');
    }
};
