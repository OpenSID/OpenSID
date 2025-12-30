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
        Schema::create('log_tolak', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('log_tolak_config_fk');
            $table->integer('id_surat')->nullable()->index('log_tolak_surat_fk');
            $table->longText('keterangan');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
            $table->integer('id_surat_dinas')->nullable()->index('log_tolak_surat_dinas_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_tolak');
    }
};
