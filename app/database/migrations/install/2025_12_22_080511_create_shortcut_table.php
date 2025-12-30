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
        Schema::create('shortcut', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->index('shortcut_config_id_foreign');
            $table->string('judul', 50);
            $table->string('raw_query', 150)->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('warna', 25)->nullable();
            $table->integer('urut')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortcut');
    }
};
