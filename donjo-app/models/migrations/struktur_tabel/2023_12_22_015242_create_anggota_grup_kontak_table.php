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
        Schema::create('anggota_grup_kontak', function (Blueprint $table) {
            $table->integer('id_grup_kontak', true);
            $table->integer('config_id')->nullable()->index('anggota_grup_kontak_config_fk');
            $table->integer('id_grup')->index('anggota_grup_kontak_ke_kontak_grup');
            $table->integer('id_kontak')->nullable()->index('anggota_grup_kontak_ke_kontak');
            $table->integer('id_penduduk')->nullable()->index('id_penduduk');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota_grup_kontak');
    }
};
