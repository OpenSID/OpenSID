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
        Schema::create('log_backup', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('config_id')->index('log_backup_config_fk');
            $table->string('ukuran', 50)->nullable();
            $table->string('path', 150)->nullable();
            $table->boolean('permanen')->default(false);
            $table->timestamp('downloaded_at')->nullable();
            $table->integer('status')->default(0);
            $table->integer('pid_process');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_backup');
    }
};
