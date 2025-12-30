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
        Schema::create('produk_kategori', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('produk_kategori_config_fk');
            $table->string('kategori', 50)->nullable();
            $table->string('slug', 100)->nullable();
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_kategori');
    }
};
