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
        Schema::create('pesan', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('config_id')->nullable()->index('pesan_config_fk');
            $table->string('judul')->nullable();
            $table->string('jenis', 50);
            $table->integer('sudah_dibaca')->default(1);
            $table->integer('diarsipkan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesan');
    }
};
