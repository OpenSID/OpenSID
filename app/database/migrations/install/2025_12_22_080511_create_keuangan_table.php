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
        Schema::create('keuangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->index('keuangan_config_id_foreign');
            $table->char('template_uuid', 36)->index('keuangan_template_uuid_foreign');
            $table->string('tahun');
            $table->decimal('anggaran', 65)->default(0);
            $table->decimal('realisasi', 65)->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
