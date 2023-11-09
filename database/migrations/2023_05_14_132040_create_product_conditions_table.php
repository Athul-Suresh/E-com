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
        Schema::dropIfExists('product_conditions');
        Schema::create('product_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->tinyInteger('status')->default(1)->comment('1 active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_conditions');
    }
};
