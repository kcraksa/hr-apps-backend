<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::create('claim_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('claim_id');
            $table->string('attachment');
            $table->timestamps();

            $table->foreign('claim_id')->references('id')->on('claims')->onDelete('cascade');
            $table->index('claim_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('claim_attachments');
    }
}