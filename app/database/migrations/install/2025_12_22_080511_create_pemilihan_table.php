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
        Schema::create('pemilihan', function (Blueprint $table) {
            $table->char('uuid', 36)->primary();
            $table->integer('config_id')->index('pemilihan_config_id_foreign');
            $table->string('judul', 100);
            $table->date('tanggal');
            $table->integer('status')->default(0);
            $table->text('keterangan');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

            $table->unique(['uuid', 'config_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilihan');
    }
};
