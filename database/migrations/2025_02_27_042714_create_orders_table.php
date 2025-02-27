<?php

use App\Enums\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('The user who placed the order');
            $table->decimal('total_price', 10, 2)->default(0.00)->comment('Total price of the order');
            $table->enum('status', [
                OrderStatus::PENDING->value,
                OrderStatus::COMPLETED->value,
                OrderStatus::CANCELED->value,
            ])->default(OrderStatus::PENDING->value)->comment('The status of the order');

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
