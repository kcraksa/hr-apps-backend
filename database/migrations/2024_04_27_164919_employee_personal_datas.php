<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_personal_datas', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 8);
            $table->foreign('nip')->references('nip')->on('employees')->onDelete('cascade');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->string('bloodtype')->nullable();
            $table->string('placeofbirth')->nullable();
            $table->date('dateofbirth')->nullable();
            $table->unsignedBigInteger('religion')->nullable();
            $table->foreign('religion')->references('id')->on('religions')->onDelete('set null');
            $table->string('nationality')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_personal_datas');
    }
};
