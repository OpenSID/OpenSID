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
        Schema::create('log_restore_desa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('log_restore_desa_config_fk');
            $table->string('ukuran', 50)->nullable();
            $table->string('path', 150)->nullable();
            $table->timestamp('restore_at')->nullable();
            $table->integer('status')->default(0);
            $table->integer('pid_process')->nullable();
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
        Schema::dropIfExists('log_restore_desa');
    }
};
