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
        Schema::create('polygon', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('polygon_config_fk');
            $table->string('nama', 50);
            $table->string('simbol', 50)->nullable();
            $table->string('color', 25)->nullable();
            $table->integer('tipe')->nullable()->default(0);
            $table->integer('parrent')->nullable()->default(1)->index('parrent');
            $table->integer('enabled')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polygon');
    }
};
