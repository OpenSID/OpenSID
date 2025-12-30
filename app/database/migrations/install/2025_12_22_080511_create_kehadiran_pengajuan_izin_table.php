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
        Schema::create('kehadiran_pengajuan_izin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->nullable();
            $table->integer('id_pamong');
            $table->enum('jenis_izin', ['izin', 'sakit', 'dinas_luar_kota', 'cuti', 'lainnya'])->comment('Jenis izin yang diajukan');
            $table->date('tanggal_mulai')->comment('Tanggal mulai izin');
            $table->date('tanggal_selesai')->comment('Tanggal selesai izin');
            $table->text('keterangan')->comment('Keterangan alasan izin');
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending')->comment('Status persetujuan');
            $table->integer('approved_by')->nullable()->index('pengajuan_izin_approved_by_fk');
            $table->dateTime('tanggal_approval')->nullable()->comment('Tanggal approval/reject');
            $table->text('keterangan_approval')->nullable()->comment('Keterangan dari atasan');
            $table->string('lampiran')->nullable()->comment('File lampiran (untuk sakit, dll)');
            $table->timestamps();

            $table->index(['config_id', 'status_approval'], 'pengajuan_izin_config_status_idx');
            $table->index(['config_id', 'tanggal_mulai'], 'pengajuan_izin_config_tanggal_idx');
            $table->index(['id_pamong', 'status_approval'], 'pengajuan_izin_pamong_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_pengajuan_izin');
    }
};
