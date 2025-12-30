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
        Schema::create('teks_berjalan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('teks_berjalan_config_fk');
            $table->text('teks')->nullable();
            $table->integer('urut')->nullable();
            $table->boolean('status')->default(false);
            $table->tinyInteger('tipe')->nullable()->default(1);
            $table->string('tautan', 150)->nullable();
            $table->string('judul_tautan', 150)->nullable();
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
        Schema::dropIfExists('teks_berjalan');
    }
};
