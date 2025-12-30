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
        Schema::create('dtks_ref_lampiran', function (Blueprint $table) {
            $table->integer('id_dtks')->index('fk_ref_lampiran_dtks');
            $table->integer('id_lampiran')->index('fk_lampiran_dtks');
            $table->integer('config_id')->nullable()->index('dtks_ref_lampiran_config_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtks_ref_lampiran');
    }
};
