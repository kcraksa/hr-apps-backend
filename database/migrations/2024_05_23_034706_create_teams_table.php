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
        Schema::create('teams', function (Blueprint $table) {
            $table->id(); // BIGINT(19) NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->string('name'); // VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci'
            $table->unsignedBigInteger('section_id')->nullable(); // BIGINT(19) NULL DEFAULT NULL
            $table->timestamp('created_at')->useCurrent(); // TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->nullable(); // TIMESTAMP NULL DEFAULT NULL

            // Using BTREE is default in Laravel migrations, so no need to specify
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
