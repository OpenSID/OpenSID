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
        Schema::create('buku_pertanyaan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('buku_pertanyaan_config_fk');
            $table->text('pertanyaan')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_pertanyaan');
    }
};
