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
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('pengaduan_config_fk');
            $table->integer('id_pengaduan')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('nama', 100);
            $table->string('email', 100)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('judul', 100)->nullable();
            $table->text('isi');
            $table->integer('status')->default(1)->comment('1 = menunggu proses, 2 = Sedang Diproses, 3 = Selesai Diproses');
            $table->string('foto', 100)->nullable();
            $table->string('ip_address', 100);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengaduan');
    }
};
