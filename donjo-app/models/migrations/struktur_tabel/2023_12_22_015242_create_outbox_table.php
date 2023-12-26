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
        Schema::create('outbox', function (Blueprint $table) {
            $table->timestamp('UpdatedInDB')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('InsertIntoDB')->useCurrent();
            $table->timestamp('SendingDateTime')->useCurrent();
            $table->time('SendBefore')->default('23:59:59');
            $table->time('SendAfter')->default('00:00:00');
            $table->text('Text')->nullable();
            $table->string('DestinationNumber', 20)->default('');
            $table->enum('Coding', ['Default_No_Compression', 'Unicode_No_Compression', '8bit', 'Default_Compression', 'Unicode_Compression'])->default('Default_No_Compression');
            $table->text('UDH')->nullable();
            $table->integer('Class')->nullable()->default(-1);
            $table->text('TextDecoded');
            $table->increments('ID');
            $table->integer('config_id')->nullable();
            $table->enum('MultiPart', ['false', 'true'])->nullable()->default('false');
            $table->integer('RelativeValidity')->nullable()->default(-1);
            $table->string('SenderID')->nullable();
            $table->timestamp('SendingTimeOut')->nullable();
            $table->enum('DeliveryReport', ['default', 'yes', 'no'])->nullable()->default('default');
            $table->text('CreatorID')->nullable();

            $table->index(['config_id', 'SenderID'], 'outbox_sender_config');
            $table->index(['SendingDateTime', 'SendingTimeOut'], 'outbox_date_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outbox');
    }
};
