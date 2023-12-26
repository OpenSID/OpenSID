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
        Schema::create('log_notifikasi_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('id_user');
            $table->integer('config_id');
            $table->string('judul');
            $table->text('isi');
            $table->string('image')->nullable();
            $table->string('payload');
            $table->integer('read');
            $table->timestamps();

            $table->index(['id', 'created_at', 'read', 'config_id'], 'log_notifikasi_admin_id_created_at_read_device_config_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_notifikasi_admin');
    }
};
