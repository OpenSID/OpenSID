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
        Schema::create('theme', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->index('theme_config_id_foreign');
            $table->string('nama', 50)->default('0');
            $table->string('slug', 60)->nullable();
            $table->string('versi', 10)->nullable();
            $table->tinyInteger('sistem')->default(0);
            $table->string('path', 100)->default('');
            $table->tinyInteger('status')->default(0);
            $table->text('keterangan')->nullable();
            $table->text('opsi')->nullable();
            $table->timestamps();

            $table->unique(['slug', 'config_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme');
    }
};
