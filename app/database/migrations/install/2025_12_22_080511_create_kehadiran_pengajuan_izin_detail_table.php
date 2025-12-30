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
        Schema::create('kehadiran_pengajuan_izin_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->nullable();
            $table->unsignedBigInteger('pengajuan_izin_id')->index('pengajuan_detail_header_fk')->comment('FK ke tabel pengajuan izin');
            $table->date('tanggal')->comment('Tanggal izin spesifik');
            $table->enum('jenis_izin', ['izin', 'sakit', 'dinas_luar_kota', 'cuti', 'lainnya'])->comment('Jenis izin (copy dari header)');
            $table->integer('id_pamong')->index('pengajuan_detail_pamong_fk')->comment('ID pamong (copy dari header)');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('Status approval (copy dari header)');
            $table->timestamps();

            $table->index(['config_id', 'tanggal'], 'pengajuan_detail_config_tanggal_idx');
            $table->index(['config_id', 'tanggal', 'status'], 'pengajuan_detail_config_tanggal_status_idx');
            $table->index(['config_id', 'id_pamong', 'tanggal'], 'pengajuan_detail_pamong_tanggal_idx');
            $table->index(['tanggal', 'jenis_izin'], 'pengajuan_detail_tanggal_jenis_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_pengajuan_izin_detail');
    }
};
