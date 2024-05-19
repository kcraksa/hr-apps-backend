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
        Schema::create('increments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('source', 100);
            $table->string('code', 100);
            $table->string('year', 4);
            $table->string('month', 2);
            $table->string('date', 2);
            $table->tinyInteger('increment')->default(0);
            $table->timestamp('updated_at');
            $table->primary('id');
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('increments');
    }
};
