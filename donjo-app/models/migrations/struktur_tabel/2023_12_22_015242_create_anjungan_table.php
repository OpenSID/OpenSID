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
        Schema::create('anjungan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('anjungan_config_fk');
            $table->string('ip_address', 100);
            $table->string('keterangan', 300)->nullable();
            $table->boolean('keyboard')->nullable()->default(true);
            $table->boolean('status')->default(true);
            $table->string('status_alasan', 100)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->string('mac_address', 100)->nullable();
            $table->string('printer_ip', 100)->nullable();
            $table->string('printer_port', 100)->nullable();
            $table->string('id_pengunjung', 100)->nullable();
            $table->tinyInteger('tipe')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anjungan');
    }
};
