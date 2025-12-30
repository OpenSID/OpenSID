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
        Schema::create('anjungan_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('anjungan_menu_config_fk');
            $table->string('nama');
            $table->text('icon')->nullable();
            $table->text('link');
            $table->tinyInteger('link_tipe');
            $table->tinyInteger('urut');
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anjungan_menu');
    }
};
