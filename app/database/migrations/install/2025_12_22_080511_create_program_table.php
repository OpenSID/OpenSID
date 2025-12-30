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
        Schema::create('program', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('nama', 100);
            $table->string('slug')->nullable();
            $table->integer('sasaran');
            $table->text('kk_level')->nullable();
            $table->string('ndesc', 500)->nullable();
            $table->date('sdate');
            $table->date('edate');
            $table->char('asaldana', 30)->nullable();
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
        Schema::dropIfExists('program');
    }
};
