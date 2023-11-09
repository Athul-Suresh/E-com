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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('slug')->unique();
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')
            ->references('id')
            ->on('brands')
            ->onDelete('cascade');

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')
            ->references('id')
            ->on('units')
            ->onDelete('cascade');

            $table->unsignedBigInteger('condition_id');
            $table->foreign('condition_id')
            ->references('id')
            ->on('product_conditions')
            ->onDelete('cascade');

            $table->unsignedBigInteger('voucher_id');
            $table->foreign('voucher_id')
            ->references('id')
            ->on('vouchers')
            ->onDelete('cascade');


            $table->decimal('purchase_price',22,3)->default(0.000);
            $table->decimal('unit_price',22,3)->default(0.000);
            $table->decimal('offer_price',22,3)->default(0.000);

            $table->integer('stock');

            $table->integer('discount_type')->default(1)->comment('1-flat,2-percentage');
            $table->decimal('discount',22,3)->default(0.000);

            $table->text('short_description');
            $table->text('long_description');

            $table->string('thumbnail');


            $table->tinyInteger('featured')->default(1)->comment('1 active');
            $table->tinyInteger('status')->default(1)->comment('1 active');
            $table->tinyInteger('refundable')->default(1)->comment('1 active');
            $table->tinyInteger('cod')->default(1)->comment('1 active');
            $table->tinyInteger('warranty')->default(1)->comment('1 active');

            $table->decimal('min_qty',22,2)->default(0.00);
            $table->decimal('max_qty',22,2)->default(0.00);

            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keyword')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
