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
        Schema::create('setting_modul', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('modul', 50);
            $table->string('slug', 100)->nullable();
            $table->string('url', 50);
            $table->boolean('aktif')->default(false);
            $table->string('ikon', 50)->nullable()->default('');
            $table->integer('urut')->nullable();
            $table->boolean('level')->default(false);
            $table->boolean('hidden')->default(false);
            $table->string('ikon_kecil', 50)->nullable()->default('');
            $table->integer('parent')->nullable()->default(0);

            $table->unique(['config_id', 'slug'], 'slug_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_modul');
    }
};
