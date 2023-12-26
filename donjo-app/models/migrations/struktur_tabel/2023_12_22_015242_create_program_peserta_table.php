<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_peserta', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('peserta', 30);
            $table->integer('program_id');
            $table->string('no_id_kartu', 60)->nullable();
            $table->string('kartu_nik', 30);
            $table->string('kartu_nama', 100);
            $table->string('kartu_tempat_lahir', 100)->default('');
            $table->date('kartu_tanggal_lahir');
            $table->string('kartu_alamat', 200)->default('');
            $table->string('kartu_peserta', 100)->nullable();
            $table->integer('kartu_id_pend')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

            $table->unique(['config_id', 'program_id', 'kartu_id_pend'], 'program_peserta_program_id_kartu_id_pend_unique_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_peserta');
    }
};
