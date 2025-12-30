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
        Schema::table('mutasi_cdesa', function (Blueprint $table) {
            $table->foreign(['id_cdesa_masuk'], 'cdesa_mutasi_fk')->references(['id'])->on('cdesa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['config_id'], 'mutasi_cdesa_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_peta'], 'mutasi_cdesa_peta_fk')->references(['id'])->on('area')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_cdesa', function (Blueprint $table) {
            $table->dropForeign('cdesa_mutasi_fk');
            $table->dropForeign('mutasi_cdesa_config_fk');
            $table->dropForeign('mutasi_cdesa_peta_fk');
        });
    }
};
