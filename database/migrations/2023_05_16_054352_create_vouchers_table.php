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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('voucher_code')->unique();
            $table->decimal('discount',11,2)->default(0);

            $table->integer('discount_type')->default(1)->comment('1-flat,2-percentage');

            $table->dateTime('expires_at')->nullable();
            $table->integer('usage_limit')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1 active');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
