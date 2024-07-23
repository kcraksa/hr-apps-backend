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
        Schema::create('attend_problems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->string('category', 50)->collation('utf8mb4_0900_ai_ci');
            $table->tinyInteger('is_personalia_approved')->default(0);
            $table->dateTime('personalia_approved_date');
            $table->unsignedBigInteger('personalia_approved_by');
            $table->tinyInteger('is_supervisor_approved')->default(0);
            $table->dateTime('supervisor_approved_date');
            $table->unsignedBigInteger('supervisor_approved_by');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('personalia_approved_by')->references('id')->on('users')->onDelete('no action');
            $table->foreign('supervisor_approved_by')->references('id')->on('users')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attend_problems');
    }
};
