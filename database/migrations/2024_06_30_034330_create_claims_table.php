<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->string('type', 70);
            $table->string('category', 70);
            $table->unsignedBigInteger('amount')->default(0);
            $table->string('description');
            $table->boolean('is_approve_supervisor')->default(false);
            $table->unsignedBigInteger('approve_supervisor_by');
            $table->timestamp('approve_supervisor_at');
            $table->boolean('is_approve_personalia')->default(false);
            $table->unsignedBigInteger('approve_personalia_by');
            $table->timestamp('approve_personalia_at');
            $table->boolean('is_approve_fa')->default(false);
            $table->unsignedBigInteger('approve_fa_by');
            $table->timestamp('approve_fa_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
}