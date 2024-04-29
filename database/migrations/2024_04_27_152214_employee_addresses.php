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
        Schema::create('employee_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->foreign('nip')->references('nip')->on('employees')->onDelete('cascade');
            $table->char('identity_number', 18);
            $table->text('address');
            $table->string('province_id', 6);
            $table->string('city_id', 6);
            $table->string('district_id', 6);
            $table->string('subdistrict_id', 6);
            $table->char('rt', 3);
            $table->char('rw', 3);
            $table->tinyInteger('is_according_ktp')->default(0);
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('subdistrict_id')->references('id')->on('subdistricts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_addresses');
    }
};
