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
        Schema::create('sentitems', function (Blueprint $table) {
            $table->timestamp('UpdatedInDB')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('InsertIntoDB')->useCurrent();
            $table->timestamp('SendingDateTime')->useCurrent();
            $table->timestamp('DeliveryDateTime')->nullable();
            $table->text('Text');
            $table->string('DestinationNumber', 20)->default('');
            $table->enum('Coding', ['Default_No_Compression', 'Unicode_No_Compression', '8bit', 'Default_Compression', 'Unicode_Compression'])->default('Default_No_Compression');
            $table->text('UDH');
            $table->string('SMSCNumber', 20)->default('');
            $table->integer('Class')->default(-1);
            $table->text('TextDecoded');
            $table->unsignedInteger('ID')->default(0);
            $table->integer('config_id')->nullable();
            $table->string('SenderID');
            $table->integer('SequencePosition')->default(1);
            $table->enum('Status', ['SendingOK', 'SendingOKNoReport', 'SendingError', 'DeliveryOK', 'DeliveryFailed', 'DeliveryPending', 'DeliveryUnknown', 'Error'])->default('SendingOK');
            $table->integer('StatusError')->default(-1);
            $table->integer('TPMR')->default(-1);
            $table->integer('RelativeValidity')->default(-1);
            $table->text('CreatorID');

            $table->index(['config_id', 'TPMR'], 'sentitems_tpmr_config');
            $table->index(['config_id', 'SenderID'], 'sentitems_sender_config');
            $table->index(['config_id', 'DeliveryDateTime'], 'sentitems_date_config');
            $table->index(['config_id', 'DestinationNumber'], 'sentitems_dest_config');
            $table->primary(['ID', 'SequencePosition']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sentitems');
    }
};
