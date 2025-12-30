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
        Schema::table('pembangunan_ref_dokumentasi', function (Blueprint $table) {
            $table->foreign(['config_id'], 'pembangunan_ref_dokumentasi_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_pembangunan'], 'pembangunan_ref_dokumentasi_pembangunan_fk')->references(['id'])->on('pembangunan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembangunan_ref_dokumentasi', function (Blueprint $table) {
            $table->dropForeign('pembangunan_ref_dokumentasi_config_fk');
            $table->dropForeign('pembangunan_ref_dokumentasi_pembangunan_fk');
        });
    }
};
