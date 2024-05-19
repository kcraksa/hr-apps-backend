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
        Schema::create('companies', function (Blueprint $table) {
            $table->string('id', 100)->collation('utf8mb4_0900_ai_ci')->primary();
            $table->string('name', 255)->collation('utf8mb4_0900_ai_ci');
            $table->string('alamat', 255)->collation('utf8mb4_0900_ai_ci');
            $table->bigInteger('district_id');
            $table->tinyInteger('status')->default(0);
            $table->timestamps(0); // equivalent to created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
