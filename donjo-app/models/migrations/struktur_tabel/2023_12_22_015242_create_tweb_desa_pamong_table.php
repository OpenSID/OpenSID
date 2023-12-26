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
        Schema::create('tweb_desa_pamong', function (Blueprint $table) {
            $table->integer('pamong_id', true);
            $table->integer('config_id')->nullable();
            $table->string('pamong_nama', 100)->nullable();
            $table->string('gelar_depan', 100)->nullable();
            $table->string('gelar_belakang', 100)->nullable();
            $table->string('pamong_nip', 20)->nullable();
            $table->string('pamong_tag_id_card', 17)->nullable();
            $table->string('pamong_pin', 15)->nullable();
            $table->string('pamong_nik', 20)->nullable();
            $table->boolean('pamong_status')->nullable()->default(true);
            $table->date('pamong_tgl_terdaftar')->nullable();
            $table->boolean('pamong_ttd')->nullable();
            $table->text('foto')->nullable();
            $table->integer('id_pend')->nullable();
            $table->string('pamong_tempatlahir', 100)->nullable();
            $table->date('pamong_tanggallahir')->nullable();
            $table->tinyInteger('pamong_sex')->nullable();
            $table->integer('pamong_pendidikan')->nullable();
            $table->integer('pamong_agama')->nullable();
            $table->string('pamong_nosk', 30)->nullable();
            $table->date('pamong_tglsk')->nullable();
            $table->string('pamong_masajab', 120)->nullable();
            $table->integer('urut')->nullable();
            $table->string('pamong_niap', 25)->nullable()->default('0');
            $table->string('pamong_pangkat', 20)->nullable();
            $table->string('pamong_nohenti', 20)->nullable();
            $table->date('pamong_tglhenti')->nullable();
            $table->boolean('pamong_ub')->default(false);
            $table->integer('atasan')->nullable();
            $table->tinyInteger('bagan_tingkat')->nullable();
            $table->integer('bagan_offset')->nullable();
            $table->string('bagan_layout', 20)->nullable();
            $table->string('bagan_warna', 25)->nullable();
            $table->integer('kehadiran')->default(1);
            $table->integer('jabatan_id');

            $table->unique(['config_id', 'pamong_tag_id_card'], 'pamong_tag_id_card_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweb_desa_pamong');
    }
};
