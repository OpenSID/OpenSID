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
        Schema::create('lampiran_surat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id');
            $table->string('slug', 200)->nullable();
            $table->string('nama', 100);
            $table->tinyInteger('jenis')->default(2);
            $table->longText('template')->nullable();
            $table->longText('template_desa')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

            $table->unique(['config_id', 'slug'], 'slug_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran_surat');
    }
};
