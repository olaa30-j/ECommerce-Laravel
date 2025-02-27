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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->comment('The order this item belongs to');
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->comment('The product in the cart');
            $table->integer('quantity')->default(1)->comment('The quantity of the product');
            $table->decimal('price', 10, 2)->default(0.00)->comment('The price of the product at the time of purchase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items'); 
        Schema::dropIfExists('orders'); 
    }
};
