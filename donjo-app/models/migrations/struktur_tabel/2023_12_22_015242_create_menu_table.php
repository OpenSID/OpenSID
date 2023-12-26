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
        Schema::create('menu', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('menu_config_fk');
            $table->string('nama', 50);
            $table->string('link', 500);
            $table->integer('parrent')->nullable()->default(0);
            $table->boolean('link_tipe')->default(false);
            $table->boolean('enabled')->nullable()->default(true);
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
        Schema::dropIfExists('menu');
    }
};
