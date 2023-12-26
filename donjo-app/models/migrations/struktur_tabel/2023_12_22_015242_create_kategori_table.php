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
        Schema::create('kategori', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('kategori_config_fk');
            $table->string('kategori', 100);
            $table->integer('tipe')->default(1);
            $table->tinyInteger('urut');
            $table->tinyInteger('enabled');
            $table->tinyInteger('parrent')->default(0);
            $table->string('slug', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori');
    }
};
