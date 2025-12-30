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
        Schema::create('dtks_lampiran', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('dtks_lampiran_config_fk');
            $table->integer('id_rtm')->nullable()->index('fk_dtks_lampiran_rtm');
            $table->string('judul', 30);
            $table->string('keterangan', 100);
            $table->text('foto');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtks_lampiran');
    }
};
