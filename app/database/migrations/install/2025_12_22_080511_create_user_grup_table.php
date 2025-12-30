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
        Schema::create('user_grup', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id');
            $table->string('nama');
            $table->string('slug')->nullable();
            $table->tinyInteger('jenis')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

            $table->unique(['config_id', 'nama'], 'nama_grup_config');
            $table->unique(['config_id', 'slug'], 'slug_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_grup');
    }
};
