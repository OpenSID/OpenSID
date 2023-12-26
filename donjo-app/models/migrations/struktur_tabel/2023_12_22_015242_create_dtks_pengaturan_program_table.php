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
        Schema::create('dtks_pengaturan_program', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->integer('versi_kuisioner');
            $table->string('kode', 25);
            $table->integer('id_bantuan')->nullable()->index('FK_dtks_p_program');
            $table->string('nilai_default', 50)->nullable();
            $table->string('target_table', 100);
            $table->text('target_field');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->unique(['config_id', 'versi_kuisioner', 'kode'], 'config_idversi_kuisionerkode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dtks_pengaturan_program');
    }
};
