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
        Schema::table('program_peserta', function (Blueprint $table) {
            $table->foreign(['config_id'], 'program_peserta_config_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['kartu_id_pend'], 'program_peserta_kartu_fk')->references(['id'])->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['program_id'], 'program_peserta_program_fk')->references(['id'])->on('program')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_peserta', function (Blueprint $table) {
            $table->dropForeign('program_peserta_config_fk');
            $table->dropForeign('program_peserta_kartu_fk');
            $table->dropForeign('program_peserta_program_fk');
        });
    }
};
