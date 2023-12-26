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
        Schema::create('keuangan_ref_bank_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ref_bank_desa_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ref_bank_desa_master_fk');
            $table->string('Tahun', 50);
            $table->string('Kd_Desa', 50);
            $table->string('Kd_Rincian', 100);
            $table->string('NoRek_Bank', 100);
            $table->string('Nama_Bank', 250);
            $table->string('Kantor_Cabang', 100)->nullable();
            $table->string('Nama_Pemilik', 100)->nullable();
            $table->string('Alamat_Pemilik', 100)->nullable();
            $table->string('No_Identitas', 20)->nullable();
            $table->string('No_Telepon', 20)->nullable();
            $table->string('ID_Bank', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ref_bank_desa');
    }
};
