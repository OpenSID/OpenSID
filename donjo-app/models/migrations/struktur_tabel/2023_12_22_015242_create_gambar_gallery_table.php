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
        Schema::create('gambar_gallery', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('gambar_gallery_config_fk');
            $table->integer('parrent')->nullable()->default(0)->index('parrent');
            $table->string('gambar', 200);
            $table->string('nama', 50);
            $table->integer('enabled')->default(1);
            $table->timestamp('tgl_upload')->useCurrent();
            $table->integer('tipe')->nullable()->default(0);
            $table->boolean('slider')->nullable();
            $table->integer('urut')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gambar_gallery');
    }
};
