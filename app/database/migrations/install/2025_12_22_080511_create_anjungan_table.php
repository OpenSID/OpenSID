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
        Schema::create('anjungan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->index('anjungan_config_fk');
            $table->string('ip_address', 100);
            $table->string('keterangan', 300)->nullable();
            $table->boolean('keyboard')->nullable()->default(true);
            $table->boolean('status')->default(true);
            $table->boolean('permohonan_surat_tanpa_akun')->default(false);
            $table->boolean('orientasi_layar')->default(true);
            $table->string('status_alasan', 100)->nullable();
            $table->string('mac_address', 100)->nullable();
            $table->string('printer_ip', 100)->nullable();
            $table->string('printer_port', 100)->nullable();
            $table->string('id_pengunjung', 100)->nullable();
            $table->text('tipe')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anjungan');
    }
};
