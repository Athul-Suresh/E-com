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
        Schema::dropIfExists('user_addresses');
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);

            $table->unsignedBigInteger('phone_1')->length(10);
            $table->unsignedBigInteger('pincode')->length(6);

            $table->string('locality', 255);
            $table->text('address');
            $table->string('city', 255)->comment('City/District/Town');



            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');




            $table->string('landmark', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('phone_2')->nullable()->length(10);
            $table->tinyInteger('address_type')->comment('Address Type, 1:home, 2:work');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
