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
        Schema::table('persil', function (Blueprint $table) {
            $table->foreign(['config_id'], 'persil_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_peta'], 'persil_peta_fk')->references(['id'])->on('area')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persil', function (Blueprint $table) {
            $table->dropForeign('persil_config_fk');
            $table->dropForeign('persil_peta_fk');
        });
    }
};
