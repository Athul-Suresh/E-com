<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('orders');

            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                $table->unsignedBigInteger('delivery_address_id');
                $table->foreign('delivery_address_id')->references('id')->on('user_addresses')->onDelete('cascade');

                $table->string('order_number')->unique();

                $table->decimal('total_amount', 20, 2);

                $table->date('order_date')->default(DB::raw('CURRENT_DATE'));

                $table->enum('payment', ['COD', 'DEBIT', 'CREDIT', 'UPI'])->default('COD');
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->timestamps();

            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
